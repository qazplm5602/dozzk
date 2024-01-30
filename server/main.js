const express = require("express");
const app = express();

app.use(express.urlencoded({extended: true}));
app.use(express.json());

const server = app.listen(3000, () => console.log("API 3000 열림"));

require("./streaming");
require("./chat")(server, app);