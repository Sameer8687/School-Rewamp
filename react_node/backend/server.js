/**
 * Server entry point.
 * Loads env, imports app, starts listening.
 */
import 'dotenv/config';
import app from './app.js';

const PORT = process.env.PORT || 5000;

app.listen(PORT, () => {
  console.log(`🚀 Olive School API  →  http://localhost:${PORT}`);
  console.log(`   ENV: ${process.env.NODE_ENV || 'development'}`);
  console.log(`   DB:  ${process.env.DB_NAME}@${process.env.DB_HOST}`);
});
