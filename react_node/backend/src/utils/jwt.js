/**
 * JWT utility helpers.
 * Centralizes token signing and verification.
 */
import jwt from 'jsonwebtoken';

const SECRET      = process.env.JWT_SECRET      || 'olive_school_jwt_secret_2026';
const EXPIRES_IN  = process.env.JWT_EXPIRES_IN  || '3h';

/** Sign a JWT payload */
export function signToken(payload) {
  return jwt.sign(payload, SECRET, { expiresIn: EXPIRES_IN });
}

/** Verify a JWT; throws on failure */
export function verifyToken(token) {
  return jwt.verify(token, SECRET);
}
