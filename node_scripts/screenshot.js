const puppeteer = require("puppeteer");

async function takeScreenshot(url, path) {
    const browser = await puppeteer.launch();
    const page = await browser.newPage();

    await page.setViewport({ width: 1366, height: 768 });

    await page.goto(url, { waitUntil: "networkidle2" });
    await page.screenshot({ path: path });
    await browser.close();
}

const url = process.argv[2];
const path = process.argv[3];
takeScreenshot(url, path).then(() => console.log("Screenshot taken for", url));
