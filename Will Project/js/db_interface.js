const { createPool } = require('mysql');
const fs = require('fs')

import dotenv from 'dotenv'
dotenv.config()
// Set up MySQL connection pool
const pool = createPool({
  host: process.env.MysqlHost,
  user: process.env.MysqlUser,
  password: process.env.MysqlPassword,
  database: process.env.MysqlDatabase,
  connectionLimit: 10
}).promise()

// Query the database
pool.query("SELECT * FROM agents", (err, result, fields) => {
  if (err) {
    console.error('Error executing query:', err);
    return;
  }

  // Convert result to JSON string
  const jsonResult = JSON.stringify(result, null, 2);

  // Ensure the Json folder exists
  const dir = './Json';
  if (!fs.existsSync(dir)){
    fs.mkdirSync(dir);
  }

  // Save the result to 'Agents.json' file
  fs.writeFile(`${dir}/Agents.json`, jsonResult, (err) => {
    if (err) {
      console.error('Error writing file:', err);
      return;
    }
    console.log('Result saved to Json/Agents.json');
  });
});

// Query the database
pool.query("SELECT * FROM properties", (err, result, fields) => {
  if (err) {
    console.error('Error executing query:', err);
    return;
  }

  // Convert result to JSON string
  const jsonResult = JSON.stringify(result, null, 2);

  // Ensure the Json folder exists
  const dir = './Json';
  if (!fs.existsSync(dir)){
    fs.mkdirSync(dir);
  }

  // Save the result to 'Agents.json' file
  fs.writeFile(`${dir}/Properties.json`, jsonResult, (err) => {
    if (err) {
      console.error('Error writing file:', err);
      return;
    }
    console.log('Result saved to Json/Agents.json');
  });
});