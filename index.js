const fetch = require('node-fetch');
const puppeteer = require('puppeteer');
const cheerio = require('cheerio');
const delay = require('delay');
const fs = require('fs')

const getData = async (url) => {
    const response = await fetch(url)
    const data = await response.json()
    return data
}

const generateString = (digit =10) => {
   const chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let result = '';
    for (let i = 0; i < digit; i++) {
        result += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    return result;
}

const generateIndoName = () => new Promise((resolve, reject) => {
    fetch('https://swappery.site/data.php?qty=1', {
        method: 'GET'
    })
        .then(res => res.json())
        .then(res => {
            resolve(res)
        })
        .catch(err => {
            reject(err)
        })
});

const functionGetLinkInbox = (email, domain) => new Promise((resolve, reject) => {
    fetch("https://www.1secmail.com/mailbox", {
        "body": "action=getMessages&login="+email+"&domain="+domain+"",
        "method": "POST",
        "headers": {
            "accept": "*/*",
            "accept-language": "en-US,en;q=0.9",
            "content-type": "application/x-www-form-urlencoded; charset=UTF-8",
            "sec-ch-ua": "\" Not A;Brand\";v=\"99\", \"Chromium\";v=\"99\", \"Google Chrome\";v=\"99\"",
            "sec-ch-ua-mobile": "?1",
            "sec-ch-ua-platform": "\"Android\"",
            "sec-fetch-dest": "empty",
            "sec-fetch-mode": "cors",
            "sec-fetch-site": "same-origin",
            "x-requested-with": "XMLHttpRequest",
            "cookie": "_pk_id.13.333e=17ce05a3a6221ce6.1633539410.; __gads=ID=c2edb0b5e2564df8-229549d05bcf00b2:T=1638607595:RT=1638607595:S=ALNI_Ma2iYB_QSKgZJa_kYXLjx0ao5tSmw; PHPSESSID=f1304e31ea5cade57dfd0aaf5c458ad6",
            "Referer": "https://www.1secmail.com/?login="+email+"&domain="+domain+"",
            "Referrer-Policy": "origin-when-cross-origin"
          }
    }).then(res => res.text())
    .then(text => {
        const $ = cheerio.load(text);
        const src = $("table > tbody > tr:nth-child(2) > td:nth-child(2) > a").attr("href");
        resolve(src);
    })
    .catch(err => reject(err));
});

const functionGetLink = (url) => new Promise((resolve, reject) => {
    textUrl = url.replace('readMessage','mailBody')
    fetch(`${textUrl}`, {
        "method": "GET",
    }).then(res => res.text())
    .then(text => {
        const $ = cheerio.load(text);
        const src = $("p:nth-child(3) > a").attr("href");
        resolve(src);
    })
    .catch(err => reject(err));
});


(async () => {

    while (true) {
        const indoName = await generateIndoName();
    const { result } = indoName;
    const name = result[0].firstname +" "+ result[0].lastname;
    const username = result[0].firstname.toLowerCase() + result[0].lastname.toLowerCase();
    const domain = "yoggm.com";
    const email = username + "@" + domain;
   
 
    const browser = await puppeteer.launch({
        headless: false,
        args: ['--no-sandbox', '--disable-setuid-sandbox']
    });
    const page = await browser.newPage();
    await page.goto('https://serpapi.com/users/sign_in');
    await page.waitForSelector('body > div.mai-wrapper.mai-login > div > div > div.col-md-6.user-message > span.alternative-message.text-right > a');
    await page.click('body > div.mai-wrapper.mai-login > div > div > div.col-md-6.user-message > span.alternative-message.text-right > a');
    await page.waitForSelector('#full_name');
    await page.type('#full_name', name, {delay: 100});
    await page.type('#email', email, {delay: 100});
    await page.type('#user_password', '12345678', {delay: 100});
    await page.type('#user_password_confirmation', '12345678', {delay: 100});
    await page.click('#sign-up-button');
    await page.waitForNavigation('https://serpapi.com/users/welcome');
    const urlInbox = await functionGetLinkInbox(username, domain);
    const linkVerify = await  functionGetLink('https://www.1secmail.com'+urlInbox);
    console.log('URL '+linkVerify)
    await page.goto(linkVerify);
    await page.waitForSelector('#btnSubmit');
    await page.click('#btnSubmit');
    await page.waitForNavigation('https://serpapi.com/dashboard');
    const api_key = await page.evaluate(() => document.querySelector('#private-api-key').value);
    console.log(api_key);
    await browser.close();
    fs.appendFile('api_key.txt', `${api_key} \n`, function (err) {
        if (err) throw err;
        console.log('Saved!');
      });
    }
})();