# 🚀 Railway Deployment Guide — Phase 11

## Status
✅ Code pushed to GitHub  
✅ Ready for Railway deployment  
✅ All migrations and seeds prepared  

---

## Step-by-Step Deployment to Railway

### Step 1: Create Railway Account & Project
1. Go to https://railway.app
2. Sign up or log in
3. Click "New Project"
4. Select "Deploy from GitHub Repo"

### Step 2: Connect GitHub Repository
1. Click "Deploy from GitHub Repo"
2. Authorize Railway to access your GitHub
3. Search for: `dwcsj-etutoring`
4. Click to deploy the repo
5. Railway will auto-detect PHP + MySQL

### Step 3: Add MySQL Database Plugin
1. In Railway Dashboard, click "Add Plugin"
2. Search for "MySQL"
3. Select "MySQL"
4. Click "Add"
5. Railway auto-generates DB credentials

### Step 4: Configure Environment Variables
Railway Dashboard → Your Project → Variables

Add these exact variables:

```
APP_NAME=PeerTutoring
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-railway-url.railway.app
APP_KEY=base64:YOUR_APP_KEY_HERE

DB_CONNECTION=mysql
DB_HOST=${{ Mysql.MYSQL_HOST }}
DB_PORT=${{ Mysql.MYSQL_PORT }}
DB_DATABASE=${{ Mysql.MYSQL_DATABASE }}
DB_USERNAME=${{ Mysql.MYSQL_USER }}
DB_PASSWORD=${{ Mysql.MYSQL_PASSWORD }}

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

### Step 5: Get Your APP_KEY
If you don't have your APP_KEY, run this locally:

```bash
php artisan key:generate --show
```

Copy the output (should start with `base64:`) and paste into Railway.

### Step 6: Deploy & Run Migrations
1. Railway will auto-deploy when you push to GitHub
2. Watch the deployment logs
3. Once deployed, migrations run automatically via Procfile

### Step 7: Seed Initial Data
After first deployment, SSH into Railway and run:

```bash
php artisan db:seed --class=AdminSeeder
php artisan db:seed --class=SubjectSeeder
php artisan db:seed --class=BookingSmokeSeeder
```

Or add to `.env` on Railway:
```
SEED_DATABASE=true
```

---

## What Railway Provides

### Automatic
✅ PHP runtime  
✅ Nginx web server  
✅ SSL certificate (HTTPS)  
✅ Domain (auto-generated)  
✅ Build & deployment  
✅ Environment variable management  

### Manual
⚙️ Database setup (we use Railway MySQL plugin)  
⚙️ Environment variables (listed above)  
⚙️ Initial data seeding  

---

## Post-Deployment Steps

### 1. Verify URL
After deployment completes:
- Railway Dashboard shows your URL
- Format: `https://xxxxx.railway.app`
- Click the URL to verify it loads

### 2. Test Login
1. Navigate to `/login`
2. Test with default user (from AdminSeeder):
   - Email: `admin@example.com`
   - Password: `password`
3. Should redirect to dashboard

### 3. Test All Three Dashboards
**Admin Dashboard:**
- URL: `/admin/dashboard`
- Login as admin
- Verify metrics display correctly
- Verify charts render with data

**Tutor Dashboard:**
- URL: `/tutor/dashboard`
- Login as tutor (create test tutor account)
- Verify tutor-specific metrics

**Student Dashboard:**
- URL: `/student/dashboard`
- Login as student (create test student account)
- Verify student-specific metrics

### 4. Check Database
Railway Dashboard → MySQL Plugin → Connect
- Verify tables created (migrations ran)
- Verify seed data exists

---

## Troubleshooting

### Deployment Fails
Check logs: Railway Dashboard → Logs tab
Common issues:
- Missing APP_KEY: Run `php artisan key:generate --show` locally
- DB credentials wrong: Copy from MySQL plugin → Connect
- Port binding: Already configured in Procfile

### 500 Error After Deploy
1. Check logs in Railway
2. Common causes:
   - APP_KEY missing: Add to variables
   - Database connection: Verify DB_* variables
   - Storage permissions: Should work (Railway auto-handles)

### Database Empty
Run seeds in Railway SSH/terminal:
```bash
php artisan migrate --force
php artisan db:seed --class=AdminSeeder
```

### Charts Not Displaying
1. Verify JavaScript console for errors
2. Check Chart.js CDN is accessible
3. Verify data is being passed from controller

---

## Your Railway Dashboard Will Show

```
Project: dwcsj-etutoring
├── Web Service (Laravel app)
│   ├── Status: ✅ Active
│   ├── URL: https://xxx.railway.app
│   ├── Deployments: 1
│   └── Logs: Real-time output
│
├── MySQL Database
│   ├── Status: ✅ Connected
│   ├── Host: railway.internal
│   ├── Database: railway
│   └── Storage: 1GB (free tier)
│
└── Environment Variables
    ├── APP_KEY: ✅ Set
    ├── DB_*: ✅ Set
    └── Other configs: ✅ Set
```

---

## Monitoring & Logs

### Real-Time Logs
Railway Dashboard → Logs → Filter by service
Shows:
- Deployment progress
- PHP errors
- SQL queries
- Application logs

### Performance
Railway auto-scales based on usage
- Free tier: Up to 5 projects
- Each project gets standard resources
- Perfect for MVP/demonstration

---

## What Your System Does on Railway

```
User visits: https://xxx.railway.app
                ↓
Nginx routes to PHP-FPM
                ↓
Laravel bootstrap loads
                ↓
Auth middleware checks login
                ↓
Role middleware checks permission
                ↓
Controller runs (queries DB)
                ↓
Blade view renders with data
                ↓
Chart.js initializes with JSON
                ↓
Browser displays dashboard
                ↓
User sees real-time analytics ✅
```

---

## Database Connection Flow

```
Railway App ──HTTP──→ Railway MySQL
             Internal Network (fast)
             
No public exposure needed
Auto-managed by Railway
Encrypted connection
```

---

## Cost Breakdown (Free Tier)

```
Laravel App:      $0 (included in hobby plan)
MySQL Database:   $0 (included in hobby plan)
Storage:          1GB (sufficient for MVP)
Bandwidth:        Unlimited (within fair use)
Total:            $0 for development/demo
```

---

## Success Criteria

After deployment, verify:

✅ URL loads without errors  
✅ Login page displays  
✅ Admin login works  
✅ Admin dashboard shows metrics  
✅ Charts render with data  
✅ Student login works  
✅ Student dashboard displays  
✅ Tutor login works  
✅ Tutor dashboard displays  
✅ Database is connected  
✅ Migrations ran automatically  
✅ Data seeded (if you ran seeds)  

---

## Share Your Live Demo

Once deployed, you can:

1. **Share the URL**: Give demo link to stakeholders
2. **Test Data**: Use seeded accounts to show functionality
3. **Screenshots**: Capture all three dashboards
4. **Performance**: Railway shows real-time stats

---

## Next Steps After Deployment

### Immediate
- Test all features thoroughly
- Gather feedback from stakeholders
- Screenshot all three dashboards

### Short-Term
- Monitor logs for errors
- Add real users and test data
- Fine-tune database queries if needed

### Medium-Term
- Phase 9: Add notification system
- Phase 10: Add advanced features
- Optimize based on real usage

---

## Environment Variables Reference

| Variable | Example | Purpose |
|----------|---------|---------|
| APP_NAME | PeerTutoring | Application name |
| APP_ENV | production | Production mode |
| APP_DEBUG | false | Never true in production |
| APP_URL | https://xxx.railway.app | Your deployed URL |
| APP_KEY | base64:xxx | Encryption key |
| DB_CONNECTION | mysql | Database type |
| DB_HOST | railway.internal | Database host |
| DB_DATABASE | railway | Database name |
| DB_USERNAME | root | Database user |
| DB_PASSWORD | xxx | Database password |

---

## Important Files for Deployment

```
✅ Procfile              → Tells Railway how to start app
✅ railway.json          → Railway config (optional)
✅ composer.json         → PHP dependencies
✅ package.json          → Node dependencies
✅ .env.example          → Environment template
✅ routes/web.php        → All routes defined
✅ app/Http/Controllers  → All controllers
✅ resources/views       → All blade templates
✅ database/migrations   → Schema
✅ database/seeders      → Initial data
```

---

## Deployment Time Estimate

| Phase | Time |
|-------|------|
| GitHub setup | ✅ Done |
| Create Railway project | 2 mins |
| Add MySQL plugin | 1 min |
| Configure variables | 5 mins |
| Deploy | 2-3 mins |
| Verify | 5 mins |
| **Total** | **15-20 mins** |

---

## Quick Checklist

- [ ] Code pushed to GitHub: ✅
- [ ] Railway account created
- [ ] Project connected to repo
- [ ] MySQL plugin added
- [ ] Environment variables set
- [ ] APP_KEY configured
- [ ] Deployment started
- [ ] URL loads successfully
- [ ] Migrations ran automatically
- [ ] Admin login works
- [ ] Dashboard displays
- [ ] Charts render

---

## Support & Troubleshooting

### Railway Docs
https://docs.railway.app/

### PHP on Railway
https://docs.railway.app/deploy/runtimes/php

### MySQL on Railway
https://docs.railway.app/databases/mysql

### Common Issues
- Port already in use: Railway handles automatically
- Storage permissions: Railway handles automatically
- Database connection: Use ${{ MySQL.* }} variables

---

**You're ready to deploy! Follow the steps above and your MVP will be live in 15-20 minutes.** 🚀

Good luck! 🎉
