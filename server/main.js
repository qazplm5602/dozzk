const mysql = require("./lib/mysql.js");

(async function() {
    const connection = await mysql.getConnection();
    console.log(await connection.Aquery("SELECT * FROM dozzk_channel"));
    connection.release();
})();