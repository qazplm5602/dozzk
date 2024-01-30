const mysql = require("mysql");
const pool = mysql.createPool({
    host: "localhost",
    user: "root",
    database: "domi_web" 
});

exports.getConnection = function() {
    return new Promise((reslove, reject) => {
        pool.getConnection((error, connection) => {
            if (error) {
                reject(error);
                return;
            }

            connection.Aquery = function(query, data) {
                return new Promise((reslove, reject) => {
                    connection.query(query, data, function(error, result) {
                        if (error || result === undefined) {
                            return reject(error);
                        }

                        reslove(result);
                    });
                });
            }

            reslove(connection);
        });
    });
}