const puppeteer = require("puppeteer");

async function takeScreenshot(url, path) {
    try {
        const browser = await puppeteer.launch({ args: ["--no-sandbox"] });
        const page = await browser.newPage();
        await page.setViewport({ width: 1366, height: 768 });
        await page.goto(url, { waitUntil: "networkidle2" });
        await page.screenshot({ path: path });
        await browser.close();
        return true; // Return true if screenshot is taken successfully
    } catch (error) {
        console.error("Error while taking screenshot:", error);
        return false; // Return false if an error occurs
    }
}

const url = process.argv[2];
const path = process.argv[3];

takeScreenshot(url, path)
    .then((success) => {
        if (success) {
            console.log("Screenshot taken for", url);
        } else {
            console.error("Failed to take screenshot for", url);
        }
    })
    .catch((error) => {
        console.error("Error occurred:", error);
    });
