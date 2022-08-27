const mysql = require('mysql'),
    sqlite3 = require('sqlite3').verbose(),
    fs = require("fs");

module.exports = class DbConnection {
    constructor(fileSystem){
        this._fileSystem = fileSystem
    }

    getDbConnection() {
        if (!this._usingSqlite()) {
            return mysql.createConnection({
                host: process.env.DB_HOST,
                user: process.env.DB_USER,
                password: process.env.DB_PASS,
                database: process.env.DB_NAME,
            });
        }
        this._checkSqliteExistsOrThrow()
        return new sqlite3.Database(process.env.DB_SQLITE);
    }

    _usingSqlite() {
        return process.env.hasOwnProperty("DB_SQLITE") && process.env.DB_SQLITE !== "";
    }

    _checkSqliteExistsOrThrow(){
        if(!fs.existsSync(process.env.DB_SQLITE)){
            if(!this._fileSystem.checkAndAwaitFileExists(process.env.DB_SQLITE)){
                throw "Waited for sqlite file to be created but it didn't happen in time"
                process.exit(1);
            }
        }
    }
}
