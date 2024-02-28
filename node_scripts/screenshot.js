const puppeteer = require("puppeteer");

async function takeScreenshot(url, path) {
    try {
        console.log("Launching browser...");
        const browser = await puppeteer.launch({
            headless: false,
            args: ["--no-sandbox"],
        });
        console.log("Browser launched successfully");

        console.log("Opening new page...");
        const page = await browser.newPage();
        console.log("New page opened successfully");

        console.log("Setting viewport...");
        await page.setViewport({ width: 1366, height: 768 });
        console.log("Viewport set successfully");

        console.log("Navigating to URL:", url);
        await page.goto(url, { waitUntil: "networkidle2" });
        console.log("Navigation to URL completed successfully");

        console.log("Taking screenshot...");
        await page.screenshot({ path: path });
        console.log("Screenshot taken successfully");

        console.log("Closing browser...");
        await browser.close();
        console.log("Browser closed successfully");

        console.log("Screenshot saved to:", path);

        return true;
    } catch (error) {
        console.error("Error while taking screenshot:", error);
        return false;
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
