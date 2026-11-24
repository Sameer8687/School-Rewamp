import * as XLSX from 'xlsx';
import crypto from 'crypto';
import BankUploadModel from '../models/BankUpload.model.js';

function normalizeHeader(val) {
  if (!val) return '';
  return String(val).toLowerCase().replace(/[^a-z0-9]/g, '');
}

function cleanText(val) {
  if (val === null || val === undefined) return '';
  return String(val).trim().replace(/\s+/g, ' ');
}

function cleanAmount(val) {
  if (val === null || val === undefined || val === '') return null;
  const cleaned = String(val).replace(/[,\u20B9INR\s]/g, '');
  const num = parseFloat(cleaned);
  return isNaN(num) ? null : num;
}

function formatDate(val) {
  if (!val) return null;
  if (val instanceof Date && !isNaN(val)) {
    return val.toISOString().slice(0, 10);
  }
  const str = String(val).trim();
  // Handle DD/MM/YYYY or DD-MM-YYYY
  const dmyMatch = str.match(/^(\d{1,2})[/-](\d{1,2})[/-](\d{4})/);
  if (dmyMatch) {
    const day = dmyMatch[1].padStart(2, '0');
    const month = dmyMatch[2].padStart(2, '0');
    const year = dmyMatch[3];
    return `${year}-${month}-${day}`;
  }
  // Try direct parse
  const d = new Date(str);
  if (!isNaN(d.getTime())) {
    return d.toISOString().slice(0, 10);
  }
  return null;
}

function formatDateTime(val) {
  if (!val) return null;
  if (val instanceof Date && !isNaN(val)) {
    return val.toISOString().slice(0, 19).replace('T', ' ');
  }
  const str = String(val).trim();
  // Try DD/MM/YYYY HH:mm:ss
  const dmyMatch = str.match(/^(\d{1,2})[/-](\d{1,2})[/-](\d{4})(?:\s+(\d{1,2}):(\d{1,2})(?::(\d{1,2}))?)?/);
  if (dmyMatch) {
    const day = dmyMatch[1].padStart(2, '0');
    const month = dmyMatch[2].padStart(2, '0');
    const year = dmyMatch[3];
    const hour = (dmyMatch[4] || '00').padStart(2, '0');
    const min = (dmyMatch[5] || '00').padStart(2, '0');
    const sec = (dmyMatch[6] || '00').padStart(2, '0');
    return `${year}-${month}-${day} ${hour}:${min}:${sec}`;
  }
  const d = new Date(str);
  if (!isNaN(d.getTime())) {
    return d.toISOString().slice(0, 19).replace('T', ' ');
  }
  return null;
}

/**
 * Upload and parse Excel/CSV bank statement
 */
export async function uploadBankFile(req, res, next) {
  try {
    let rowsData = [];
    const groupId = crypto.randomUUID();

    if (req.file) {
      // Parse Excel buffer
      const workbook = XLSX.read(req.file.buffer, { type: 'buffer', cellDates: true });
      const firstSheetName = workbook.SheetNames[0];
      const sheet = workbook.Sheets[firstSheetName];

      const rawJson = XLSX.utils.sheet_to_json(sheet, { header: 1, defval: '' });
      if (!rawJson || rawJson.length < 2) {
        return res.status(400).json({ success: false, message: 'Uploaded spreadsheet is empty or has no data rows.' });
      }

      // Detect header row (first 20 rows)
      const aliases = {
        transaction_id: ['transactionid', 'txnid', 'referenceid', 'refno', 'utr', 'chequeno'],
        value_date: ['valuedate', 'date', 'txndate'],
        txn_posted_date: ['txnposteddate', 'transactionposteddate', 'posteddate', 'txndatetime'],
        cheque_no: ['chequeno', 'chqno'],
        description: ['description', 'particulars', 'narration', 'remarks'],
        mode: ['crdr', 'mode', 'type'],
        transaction_amount: ['transactionamountinr', 'transactionamount', 'amount', 'depositamount', 'credit', 'cr'],
      };

      let headerRowIndex = -1;
      let headerMap = {};

      for (let r = 0; r < Math.min(rawJson.length, 20); r++) {
        const row = rawJson[r];
        const currentMap = {};
        row.forEach((cell, colIdx) => {
          const norm = normalizeHeader(cell);
          if (!norm) return;
          for (const [key, names] of Object.entries(aliases)) {
            if (names.some((n) => norm.includes(n))) {
              if (currentMap[key] === undefined) {
                currentMap[key] = colIdx;
              }
            }
          }
        });

        if (
          currentMap.transaction_id !== undefined &&
          currentMap.description !== undefined &&
          currentMap.transaction_amount !== undefined
        ) {
          headerRowIndex = r;
          headerMap = currentMap;
          break;
        }
      }

      if (headerRowIndex === -1) {
        return res.status(400).json({
          success: false,
          message:
            'Could not detect standard bank statement headers. Required columns: Transaction ID, Value Date, Description, and Amount.',
        });
      }

      // Parse data rows
      for (let r = headerRowIndex + 1; r < rawJson.length; r++) {
        const row = rawJson[r];
        const txnId = cleanText(row[headerMap.transaction_id]);
        if (!txnId) continue;

        const valDateRaw = headerMap.value_date !== undefined ? row[headerMap.value_date] : null;
        const postedDateRaw = headerMap.txn_posted_date !== undefined ? row[headerMap.txn_posted_date] : valDateRaw;
        const chqNo = headerMap.cheque_no !== undefined ? cleanText(row[headerMap.cheque_no]) : '';
        const desc = headerMap.description !== undefined ? cleanText(row[headerMap.description]) : '';
        const mode = headerMap.mode !== undefined ? cleanText(row[headerMap.mode]) : 'CR';
        const amt = headerMap.transaction_amount !== undefined ? cleanAmount(row[headerMap.transaction_amount]) : null;

        rowsData.push({
          transaction_id: txnId,
          value_date: formatDate(valDateRaw) || formatDate(postedDateRaw) || new Date().toISOString().slice(0, 10),
          txn_posted_date: formatDateTime(postedDateRaw) || formatDateTime(valDateRaw) || new Date().toISOString().slice(0, 19).replace('T', ' '),
          cheque_no: chqNo || null,
          description: desc,
          mode: mode || null,
          transaction_amount: amt,
        });
      }
    } else if (req.body.transactions && Array.isArray(req.body.transactions)) {
      rowsData = req.body.transactions;
    } else {
      return res.status(400).json({ success: false, message: 'Please upload an Excel/CSV file or send transactions array.' });
    }

    if (rowsData.length === 0) {
      return res.status(400).json({ success: false, message: 'No valid transaction records found in file.' });
    }

    let inserted = 0;
    let duplicates = 0;
    let failed = 0;
    const errors = [];
    const seenTxnIds = new Set();
    const uploadedBy = req.user?.id || req.user?.spruser_id || null;

    for (let i = 0; i < rowsData.length; i++) {
      const row = rowsData[i];
      const txnId = cleanText(row.transaction_id);

      if (!txnId || seenTxnIds.has(txnId)) {
        duplicates++;
        continue;
      }
      seenTxnIds.add(txnId);

      const exists = await BankUploadModel.transactionExists(txnId);
      if (exists) {
        duplicates++;
        continue;
      }

      if (row.transaction_amount === null || isNaN(row.transaction_amount) || row.transaction_amount <= 0) {
        failed++;
        errors.push({ row: i + 1, transaction_id: txnId, message: 'Invalid or missing amount' });
        continue;
      }

      try {
        await BankUploadModel.insertTransaction({
          group_id: groupId,
          transaction_id: txnId,
          value_date: row.value_date,
          txn_posted_date: row.txn_posted_date,
          cheque_no: row.cheque_no,
          description: row.description || 'Bank Fee Credit',
          mode: row.mode || 'CR',
          transaction_amount: row.transaction_amount,
          uploaded_by: uploadedBy,
        });
        inserted++;
      } catch (err) {
        failed++;
        errors.push({ row: i + 1, transaction_id: txnId, message: err.message });
      }
    }

    res.json({
      success: true,
      message: `Bank statement processed successfully. ${inserted} inserted, ${duplicates} duplicates skipped.`,
      data: {
        group_id: groupId,
        total_read: rowsData.length,
        inserted,
        duplicates,
        failed,
        errors: errors.slice(0, 20),
      },
    });
  } catch (error) {
    next(error);
  }
}

/**
 * Get filtered transactions list
 */
export async function getBankTransactions(req, res, next) {
  try {
    const { transaction_id, value_date, from_date, to_date, uploaded_at, group_id, search, limit, page } = req.query;

    const pageNum = parseInt(page, 10) || 1;
    const limitNum = parseInt(limit, 10) || 50;
    const offset = (pageNum - 1) * limitNum;

    const result = await BankUploadModel.getFilteredTransactions({
      transaction_id,
      value_date,
      from_date,
      to_date,
      uploaded_at,
      group_id,
      search,
      limit: limitNum,
      offset,
    });

    res.json({
      success: true,
      data: {
        transactions: result.transactions,
        total_count: result.total_count,
        total_amount: result.total_amount,
        page: pageNum,
        limit: limitNum,
        total_pages: Math.ceil(result.total_count / limitNum),
      },
    });
  } catch (error) {
    next(error);
  }
}

/**
 * Get upload batch history
 */
export async function getBankGroups(req, res, next) {
  try {
    const groups = await BankUploadModel.getUploadGroups();
    res.json({
      success: true,
      data: groups,
    });
  } catch (error) {
    next(error);
  }
}

/**
 * Get batch rows by group ID
 */
export async function getGroupDetails(req, res, next) {
  try {
    const { groupId } = req.params;
    const result = await BankUploadModel.getFilteredTransactions({ group_id: groupId });
    res.json({
      success: true,
      data: result,
    });
  } catch (error) {
    next(error);
  }
}

export default {
  uploadBankFile,
  getBankTransactions,
  getBankGroups,
  getGroupDetails,
};
