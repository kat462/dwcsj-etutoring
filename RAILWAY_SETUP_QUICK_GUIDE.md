# 🚀 RAILWAY SETUP — Exact Steps Needed

**Status:** Your code is on GitHub ✅  
**Railway Project:** Created ✅  
**Next:** Add MySQL + Environment Variables ⏳

---

## **What You See on Railway Right Now**

```
✅ Service: dwcsj-etutoring (PHP app)
✅ Build: Automatically Detected (Dockerfile)
✅ Start Command: php artisan serve --host=0.0.0.0 --port=$PORT
✅ Source: GitHub repo (kat462/dwcsj-etutoring)
✅ Branch: main

⚠️ MISSING: 
   • MySQL Database plugin
   • Environment Variables
   • APP_KEY
   • Database credentials
```

---

## **Step 1: Add MySQL Database Plugin**

### In Railway Dashboard:
1. Click **"+ Add"** button (top right of dashboard)
2. Search for **"MySQL"**
3. Click **MySQL** to add it
4. Railway creates database automatically

### Result:
```
✅ MySQL database created
✅ Credentials auto-generated
✅ Database name, user, password assigned
```

---

## **Step 2: Get MySQL Credentials**

After adding MySQL plugin:

1. Click on the **MySQL** service (in your project)
2. Go to **"Variables"** tab
3. You'll see:
   ```
   MYSQL_HOST=
   MYSQL_PORT=
   MYSQL_DATABASE=
   MYSQL_USER=
   MYSQL_PASSWORD=
   ```

**Write these down!** You'll need them in next step.

---

## **Step 3: Add Environment Variables to PHP App**

### In Railway Dashboard:

1. Click on the **dwcsj-etutoring** service (PHP app)
2. Go to **"Variables"** tab
3. Click **"Raw Editor"** (easier to paste multiple vars)
4. **Paste this exactly:**

```
APP_NAME=PeerTutoring
APP_ENV=production
APP_DEBUG=false
APP_URL=https://dwcsj-etutoring.up.railway.app
APP_KEY=base64:0QkIuYffEyK3sAhDJxX8PNhyvDmWvUDuhl3HqOifoWQ=

DB_CONNECTION=mysql
DB_HOST=${{ Mysql.MYSQL_HOST }}
DB_PORT=${{ Mysql.MYSQL_PORT }}
DB_DATABASE=${{ Mysql.MYSQL_DATABASE }}
DB_USERNAME=${{ Mysql.MYSQL_USER }}
DB_PASSWORD=${{ Mysql.MYSQL_PASSWORD }}

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync

MAIL_MAILER=log
MAIL_FROM_ADDRESS=noreply@peertutoring.com
```

5. Click **"Deploy"**

---

## **Step 4: Wait for Deployment**

Railway will:
1. Build the Docker image
2. Install dependencies
3. Run migrations automatically (via Procfile)
4. Start the app
5. Show ✅ "Deployment successful"

**Watch the logs** to see progress (click the **Logs** tab)

---

## **Step 5: Get Your Live URL**

After deployment finishes:

1. Go to **"Networking"** tab
2. Under **"Public Networking"**, you'll see:
   ```
   https://dwcsj-etutoring-production.up.railway.app
   ```
3. Or Railway auto-generates: `https://dwcsj-etutoring.up.railway.app`

**Copy this URL!** This is your live app.

---

## **Step 6: Test Your Deployment**

1. Visit your Railway URL
2. You should see login page
3. Login with:
   ```
   Email: admin@example.com
   Password: password
   ```
4. ✅ Admin dashboard should display
5. Check if metrics show numbers
6. Check if charts render

---

## **If You See an Error:**

### 500 Error
- Check logs (Logs tab)
- Most likely: Missing APP_KEY or DB variables
- Fix: Add variables and redeploy

### Database Connection Error
- Verify MySQL plugin is added
- Check DB_* variables match MySQL credentials
- Redeploy after fixing

### App won't start
- Check logs for specific error
- Usually: Missing dependencies or config issue
- Fix in logs and redeploy

---

## **The Three Lines You MUST Add:**

These are the most important:

```
APP_KEY=base64:0QkIuYffEyK3sAhDJxX8PNhyvDmWvUDuhl3HqOifoWQ=

DB_HOST=${{ Mysql.MYSQL_HOST }}
DB_PORT=${{ Mysql.MYSQL_PORT }}
DB_DATABASE=${{ Mysql.MYSQL_DATABASE }}
DB_USERNAME=${{ Mysql.MYSQL_USER }}
DB_PASSWORD=${{ Mysql.MYSQL_PASSWORD }}
```

---

## **Complete Setup Checklist**

- [ ] Click "+ Add" in Railway
- [ ] Search and add MySQL plugin
- [ ] Get MySQL credentials
- [ ] Go to PHP app Variables tab
- [ ] Paste all environment variables
- [ ] Click Deploy
- [ ] Wait for "Deployment successful" ✅
- [ ] Get your live URL
- [ ] Visit the URL
- [ ] Login with admin@example.com
- [ ] See admin dashboard
- [ ] ✅ SUCCESS!

---

## **What Happens Automatically**

When you deploy with these variables:

```
✅ Laravel boots in production mode
✅ Database connection established
✅ Migrations run (creates tables)
✅ App accepts requests
✅ HTTPS/SSL enabled
✅ Real-time data serves
✅ Dashboards load with metrics
✅ Charts render with data
```

---

## **Time Estimate**

- Add MySQL: 1 min
- Get credentials: 1 min
- Add variables: 3 mins
- Deploy: 2 mins
- Test: 2 mins
- **Total: ~10 mins**

---

## **Your Live URL Format**

Railway gives you format like:
```
https://dwcsj-etutoring-production.up.railway.app
```

Or just:
```
https://dwcsj-etutoring.up.railway.app
```

You can customize later with custom domain.

---

## **Success = This Happens**

After deployment:

```
🌍 URL loads → Login page displays
🔑 Login works → Admin dashboard loads
📊 Dashboard → Metrics cards show numbers
📈 Charts → Display with data
✅ Database → Connected
✅ Mobile → Responsive design works
✅ Features → All working
```

---

## **Questions?**

Check these files in your repo:
- `RAILWAY_DEPLOYMENT_GUIDE.md` (detailed guide)
- `DEPLOYMENT_CHECKLIST.md` (step checklist)
- `PHASE_11_COMPLETE.md` (full overview)

---

**Next:** Go to Railway and add MySQL + variables → Deploy

Done! 🚀
