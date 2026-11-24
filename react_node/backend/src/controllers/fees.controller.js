import FeesModel from '../models/Fees.model.js';

export const searchStudents = async (req, res) => {
  try {
    const { name, academic_year, include_inactive } = req.query;
    if (!name || name.trim().length < 2) {
      return res.json({ success: true, students: [] });
    }

    let acdmYear = academic_year;
    if (!acdmYear) {
      acdmYear = await FeesModel.getActiveAcademicYearId();
    }

    const students = await FeesModel.searchStudentsForFees(
      name,
      acdmYear,
      include_inactive === 'true' || include_inactive === 'Yes'
    );

    res.json({ success: true, students });
  } catch (error) {
    console.error('Fees search error:', error);
    res.status(500).json({ success: false, message: 'Failed to search students' });
  }
};

export const getStudentFeeLedger = async (req, res) => {
  try {
    const { studentId } = req.params;
    let { academicYear } = req.query;

    if (!academicYear) {
      academicYear = await FeesModel.getActiveAcademicYearId();
    }

    const ledger = await FeesModel.getStudentFeeLedger(studentId, academicYear);
    if (!ledger) {
      return res.status(404).json({ success: false, message: 'Student fee record not found' });
    }

    res.json({ success: true, ledger });
  } catch (error) {
    console.error('Fees ledger error:', error);
    res.status(500).json({ success: false, message: 'Failed to fetch fee ledger' });
  }
};

export const collectFees = async (req, res) => {
  try {
    const userId = req.user?.id || req.user?.spruser_id || 1;
    const {
      student_id,
      standard,
      amount,
      fees_date,
      details,
      due_date,
      academic_year,
      concession_amount,
      concession_note,
      payment_mode
    } = req.body;

    if (!student_id || (!amount && !concession_amount)) {
      return res.status(400).json({
        success: false,
        message: 'Student ID and Amount or Concession are required'
      });
    }

    const result = await FeesModel.collectFees(
      {
        student_id,
        standard,
        amount: Number(amount) || 0,
        fees_date,
        details,
        due_date,
        academic_year,
        concession_amount: Number(concession_amount) || 0,
        concession_note,
        payment_mode
      },
      userId
    );

    res.json({
      success: true,
      message: 'Fee collected successfully!',
      insertId: result.insertId
    });
  } catch (error) {
    console.error('Fee collection error:', error);
    res.status(500).json({ success: false, message: 'Failed to record fee payment' });
  }
};

export const getFeesReport = async (req, res) => {
  try {
    let { academic_year, standard } = req.query;

    if (!academic_year) {
      academic_year = await FeesModel.getActiveAcademicYearId();
    }

    const records = await FeesModel.getFeesReport(academic_year, standard);

    // Compute summary cards
    let totalBilled = 0;
    let totalCollected = 0;
    let totalExemptions = 0;
    let totalBalance = 0;

    records.forEach(r => {
      totalBilled += Number(r.total_fees) || 0;
      totalCollected += Number(r.total_paid) || 0;
      totalExemptions += Number(r.total_exemption) || 0;
      totalBalance += Number(r.balance) || 0;
    });

    res.json({
      success: true,
      records,
      summary: {
        total_students: records.length,
        total_billed: totalBilled,
        total_collected: totalCollected,
        total_exemptions: totalExemptions,
        total_balance: totalBalance
      }
    });
  } catch (error) {
    console.error('Fees report error:', error);
    res.status(500).json({ success: false, message: 'Failed to generate fees report' });
  }
};

export const getReceiptDetails = async (req, res) => {
  try {
    const { studentId } = req.params;
    const { academicYear } = req.query;

    const receiptData = await FeesModel.getReceiptDetails(studentId, academicYear);
    if (!receiptData) {
      return res.status(404).json({ success: false, message: 'Receipt not found' });
    }

    res.json({ success: true, receipt: receiptData, data: receiptData });
  } catch (error) {
    console.error('Receipt error:', error);
    res.status(500).json({ success: false, message: 'Failed to get receipt details' });
  }
};

export const getFeeStructure = async (req, res) => {
  try {
    let { academic_year } = req.query;

    if (!academic_year) {
      academic_year = await FeesModel.getActiveAcademicYearId();
    }

    const structure = await FeesModel.getFeeStructure(academic_year);
    res.json({ success: true, structure });
  } catch (error) {
    console.error('Fee structure error:', error);
    res.status(500).json({ success: false, message: 'Failed to fetch fee structure' });
  }
};

export const saveFeeStructure = async (req, res) => {
  try {
    const standard = req.body.standard || req.body.class || req.body.std;
    const rawFees = req.body.fees !== undefined ? req.body.fees : (req.body.total_fees !== undefined ? req.body.total_fees : req.body.amount);
    const academic_year = req.body.academic_year || req.body.academicYear || (await FeesModel.getActiveAcademicYearId());

    if (!standard || rawFees === undefined) {
      return res.status(400).json({
        success: false,
        message: 'Standard and fees are required'
      });
    }

    const fees = Number(rawFees) || 0;
    const result = await FeesModel.saveFeeStructure(standard, fees, academic_year);
    res.json({ success: true, message: 'Fee structure saved successfully', result });
  } catch (error) {
    console.error('Save fee structure error:', error);
    res.status(500).json({ success: false, message: 'Failed to save fee structure' });
  }
};

export const getReconciliation = async (req, res) => {
  try {
    const today = new Date().toISOString().split('T')[0];
    const from_date = req.query.from_date || today;
    const to_date = req.query.to_date || today;

    const bankTransactions = await FeesModel.getBankTransactions(from_date, to_date);
    const dailyFees = await FeesModel.getDailyFees(from_date, to_date);

    const bankTotal = bankTransactions.reduce((acc, t) => acc + (Number(t.transaction_amount) || 0), 0);
    const dailyTotal = dailyFees.reduce((acc, f) => acc + (Number(f.sprfees_amount) || 0), 0);

    res.json({
      success: true,
      from_date,
      to_date,
      bank_transactions: bankTransactions,
      bank_total: bankTotal,
      daily_fees: dailyFees,
      daily_total: dailyTotal,
      difference: Math.abs(bankTotal - dailyTotal)
    });
  } catch (error) {
    console.error('Reconciliation error:', error);
    res.status(500).json({ success: false, message: 'Failed to fetch reconciliation data' });
  }
};

export const updateFeeDate = async (req, res) => {
  try {
    const { sprfees_pk, student_id, update_date, current_date } = req.body;
    if (!sprfees_pk || !student_id || !update_date || !current_date) {
      return res.status(400).json({ success: false, message: 'Missing required parameters' });
    }

    const updated = await FeesModel.updateFeeDate(sprfees_pk, student_id, update_date, current_date);
    if (!updated) {
      return res.status(404).json({ success: false, message: 'Record not found or already up to date' });
    }

    res.json({ success: true, message: 'Fee date updated successfully' });
  } catch (error) {
    console.error('Update fee date error:', error);
    res.status(500).json({ success: false, message: 'Failed to update fee date' });
  }
};

export const getFeesExecutiveDashboard = async (req, res, next) => {
  try {
    const { academic_year, academicYear, from_date, fromDate, to_date, toDate } = req.query;
    const data = await FeesModel.getFeesExecutiveDashboard({
      academicYear: academic_year || academicYear,
      fromDate: from_date || fromDate,
      toDate: to_date || toDate,
    });
    res.json({ success: true, ...data });
  } catch (error) {
    console.error('Fees executive dashboard error:', error);
    res.status(500).json({ success: false, message: 'Failed to fetch executive fees dashboard' });
  }
};

export const getFeesComments = async (req, res) => {
  try {
    const { academic_year, academicYear } = req.query;
    const comments = await FeesModel.getFeesComments(academic_year || academicYear);
    res.json({
      success: true,
      comments,
      count: comments.length
    });
  } catch (error) {
    console.error('Fees comments error:', error);
    res.status(500).json({ success: false, message: 'Failed to fetch fees comments' });
  }
};

export const getAllPreviousFeesReport = async (req, res) => {
  try {
    const data = await FeesModel.getAllPreviousFeesReport();
    res.json({ success: true, ...data });
  } catch (error) {
    console.error('All previous fees report error:', error);
    res.status(500).json({ success: false, message: 'Failed to fetch all previous fees report' });
  }
};

export const getLastYearFeesReport = async (req, res) => {
  try {
    const { academicYearId, academic_year } = req.query;
    const data = await FeesModel.getLastYearFeesReport(academicYearId || academic_year);
    res.json({ success: true, ...data });
  } catch (error) {
    console.error('Last year fees report error:', error);
    res.status(500).json({ success: false, message: 'Failed to fetch last year fees report' });
  }
};

export const getDailyFeesReport = async (req, res) => {
  try {
    const { from_date, fromDate, to_date, toDate, academic_year, academicYear, standard, division, new_admission } = req.query;
    const data = await FeesModel.getDailyFeesReport({
      fromDate: from_date || fromDate,
      toDate: to_date || toDate,
      academicYear: academic_year || academicYear,
      standard,
      division,
      newAdmission: new_admission,
    });
    res.json({ success: true, ...data });
  } catch (error) {
    console.error('Daily fees report error:', error);
    res.status(500).json({ success: false, message: 'Failed to fetch daily fees report' });
  }
};
