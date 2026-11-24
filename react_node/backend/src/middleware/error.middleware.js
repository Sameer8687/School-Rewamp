/**
 * Global error-handling middleware.
 * Catches any unhandled error thrown in async controllers.
 */
export function errorHandler(err, req, res, _next) {
  console.error('[ERROR]', err.message || err);
  const status = err.status || err.statusCode || 500;
  res.status(status).json({
    success: false,
    message: err.message || 'Internal server error.',
  });
}

/**
 * 404 handler — must be registered after all routes.
 */
export function notFound(req, res) {
  res.status(404).json({
    success: false,
    message: `Route ${req.method} ${req.path} not found.`,
  });
}
