/**
 * Shared response helpers.
 */

/** Format a MySQL date object or string to YYYY-MM-DD */
export function formatDate(d) {
  if (!d) return null;
  return new Date(d).toISOString().slice(0, 10);
}

/** Build Indian locale date-time string (mirrors CI3 date('d M, h:i A')) */
export function indianDateTime() {
  const now = new Date();
  const date = now.toLocaleDateString('en-IN', { day: '2-digit', month: 'short' });
  const time = now.toLocaleTimeString('en-IN', { hour: '2-digit', minute: '2-digit', hour12: true });
  return `${date}, ${time}`;
}

/** Safe parseInt with fallback */
export function toInt(v, fallback = 0) {
  const n = parseInt(v, 10);
  return Number.isNaN(n) ? fallback : n;
}

/** Send a standardised success JSON response */
export function success(res, data, statusCode = 200) {
  return res.status(statusCode).json({ success: true, ...data });
}

/** Send a standardised error JSON response */
export function failure(res, message, statusCode = 400) {
  return res.status(statusCode).json({ success: false, message });
}
