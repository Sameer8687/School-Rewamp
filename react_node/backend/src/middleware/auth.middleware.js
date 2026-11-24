/**
 * Auth Middleware — JWT verification + role authorization.
 *
 * CI3 equivalent: MY_Controller.php constructor session checks:
 *   admin   → 'oliveSchoolDashboardUser'
 *   teacher → 'oliveMobileSystemUser'
 *   student → 'StudentProfileSession'
 */
import { verifyToken } from '../utils/jwt.js';
import { failure } from '../utils/helpers.js';

/**
 * Verify Bearer token (or fallback HTTP-only cookie).
 * Attaches decoded payload to req.user.
 */
export function authenticate(req, res, next) {
  let token = null;

  const authHeader = req.headers.authorization;
  if (authHeader?.startsWith('Bearer ')) {
    token = authHeader.split(' ')[1];
  }

  if (!token && req.cookies?.token) {
    token = req.cookies.token;
  }

  if (!token) {
    return failure(res, 'Access denied. No token provided.', 401);
  }

  try {
    req.user = verifyToken(token);
    next();
  } catch {
    return failure(res, 'Invalid or expired token.', 401);
  }
}

/**
 * Role-based access guard.
 * Usage: router.get('/admin-only', authenticate, authorize('admin'), handler)
 */
export function authorize(...roles) {
  return (req, res, next) => {
    if (!req.user || !roles.includes(req.user.role)) {
      return failure(res, 'You do not have permission to access this resource.', 403);
    }
    next();
  };
}
