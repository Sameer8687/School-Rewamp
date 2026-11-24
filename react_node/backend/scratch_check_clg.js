import pool from './src/config/db.js';

async function check() {
  try {
    const [tables] = await pool.query("SHOW TABLES LIKE 'clg_%'");
    for (const t of tables) {
      const tableName = Object.values(t)[0];
      const [cols] = await pool.query(`DESCRIBE ${tableName}`);
      console.log(`\n=== Table: ${tableName} ===`);
      console.log(cols.map(c => `${c.Field} (${c.Type})`).join(', '));
    }
  } catch (err) {
    console.error(err);
  } finally {
    process.exit(0);
  }
}

check();
