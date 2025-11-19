# 📚 Student Dashboard - Visual Guide

## Dashboard Layout

```
┌─────────────────────────────────────────────────────────────────┐
│                                                                 │
│  Welcome back, [Student Name]! 👋                              │
│  Track your learning progress and manage your tutoring sessions│
│                                                                 │
└─────────────────────────────────────────────────────────────────┘

┌─────────────┬─────────────┬─────────────┬─────────────────────┐
│ 📅 Upcoming │ ⏳ Pending  │ ✅ Completed│ ⭐ Feedback Given   │
│  Sessions   │  Requests   │  Sessions   │                     │
│      1      │      0      │      1      │        1            │
└─────────────┴─────────────┴─────────────┴─────────────────────┘

┌─────────────────────────────┬──────────────────────────────────┐
│  Sessions Per Month         │  Sessions by Status              │
│  (Line Chart)               │  (Doughnut Chart)                │
│                             │                                  │
│  Data: 6 months of data     │  Pending | Scheduled | Completed │
│                             │  Cancelled                       │
└─────────────────────────────┴──────────────────────────────────┘

┌──────────────┬──────────────┬──────────────────────────────────┐
│ 📚 Book a    │ 🔍 Browse    │ 📅 View Calendar                 │
│  Tutor       │  Tutors      │                                  │
└──────────────┴──────────────┴──────────────────────────────────┘

┌────────────────────────────────────────┬──────────────────────┐
│                                        │                      │
│  ⏳ Pending Requests                   │  👥 Recent Tutors    │
│  (Yellow cards)                        │                      │
│  • Tutor name                          │  1. Avatar initials  │
│  • Subject                             │     Tutor name       │
│  • Status: Pending                     │     ⭐ Rating       │
│  • Time requested                      │     [Book Again]     │
│                                        │                      │
│  📅 Scheduled Sessions                 │  2. Avatar initials  │
│  (Blue cards)                          │     Tutor name       │
│  • Tutor name                          │     ⭐ Rating       │
│  • Subject                             │     [Book Again]     │
│  • Date & Time                         │                      │
│  • Status: Scheduled                   │  3. Avatar initials  │
│                                        │     Tutor name       │
│  ✅ Completed Sessions                 │     ⭐ Rating       │
│  (Green cards)                         │     [Book Again]     │
│  • Tutor name                          │                      │
│  • Subject                             │  [View All Tutors]   │
│  • Completion date                     │                      │
│  • Feedback from tutor (if given)      │                      │
│  • Star rating display                 │                      │
│                                        │                      │
└────────────────────────────────────────┴──────────────────────┘
```

---

## Color Coding Legend

| Element | Color | Meaning |
|---------|-------|---------|
| Metric Card - Upcoming | Blue | Future sessions scheduled |
| Metric Card - Pending | Yellow | Awaiting tutor response |
| Metric Card - Completed | Green | Finished lessons |
| Metric Card - Feedback | Purple | Feedback given to tutors |
| Pending Request Card | Yellow border + bg | Unresponded booking |
| Scheduled Session Card | Blue border + bg | Confirmed upcoming lesson |
| Completed Session Card | Green border + bg | Finished lesson with feedback |
| Status Badge - Pending | Yellow | Waiting for response |
| Status Badge - Scheduled | Blue | Confirmed and scheduled |
| Status Badge - Completed | Green | Lesson completed |
| Status Badge - Cancelled | Red | Booking cancelled |

---

## Charts Explained

### Chart 1: Sessions Per Month (Line Chart)

**What it shows:**
- Number of completed sessions per month
- Last 6 months of learning activity
- Trend line showing learning progression

**Data points:**
- X-axis: Month abbreviations (e.g., "May 25", "Jun 25")
- Y-axis: Number of sessions (0, 1, 2, 3, etc.)
- Green line with points showing trend

**Interaction:**
- Hover over points to see exact number
- Smooth line animation

**Interpretation:**
- Rising line = increasing learning activity
- Flat line = consistent engagement
- Declining line = fewer sessions booked

---

### Chart 2: Sessions by Status (Doughnut Chart)

**What it shows:**
- Breakdown of all bookings by status
- Visual proportion of each status type

**Colors:**
- **Yellow slice:** Pending (waiting for tutor)
- **Blue slice:** Scheduled (confirmed)
- **Green slice:** Completed (finished)
- **Red slice:** Cancelled (not happening)

**Interaction:**
- Click legend items to show/hide slices
- Hover over slice to see exact number

**Interpretation:**
- Large green slice = many completed sessions (good!)
- Large yellow slice = many pending requests (may indicate slow tutor response)
- Small yellow/red = good acceptance rate and low cancellations

---

## Widget Explanations

### Pending Requests Widget

**Shows:** Bookings where tutor hasn't responded yet

**Card Layout:**
```
┌─────────────────────────────────┐
│ Tutor Name          [Pending]   │
│ 📚 Subject Name                 │
│ Your request notes here          │
│ Requested: 2 hours ago          │
└─────────────────────────────────┘
```

**Status Meaning:**
- Yellow badge = Tutor hasn't accepted or declined yet
- Time stamp = How long you've been waiting

**What to do:**
- If too long, consider messaging the tutor
- Try booking another tutor if this one doesn't respond

---

### Scheduled Sessions Widget

**Shows:** Confirmed lessons coming up (next 30 days)

**Card Layout:**
```
┌─────────────────────────────────┐
│ Tutor Name        [Scheduled]   │
│ 📚 Subject Name                 │
│ 🕐 Nov 25, 2025 at 2:00 PM    │
│ Optional notes about session    │
└─────────────────────────────────┘
```

**Status Meaning:**
- Blue badge = Tutor accepted and session is booked
- Date/time = When your lesson is

**What to do:**
- Mark your calendar
- Prepare materials for the lesson
- Join early if using video conferencing

---

### Completed Sessions Widget

**Shows:** Lessons you've finished (with feedback from tutor)

**Card Layout:**
```
┌─────────────────────────────────┐
│ Tutor Name         [Completed]  │
│ 📚 Subject Name                 │
│ Completed: Nov 20, 2025         │
│ ⭐⭐⭐⭐⭐ 5/5 Excellent    │
│ "Great job on those calculus    │
│  problems! Keep practicing!"    │
└─────────────────────────────────┘
```

**Understanding the Feedback:**
- Star count (1-5) = Tutor's rating of your performance
- Comment = Specific feedback or encouragement
- Green card = Lesson complete

**What to do:**
- Read feedback for improvement areas
- Book the same tutor again if you liked them
- Move on to next topic with confidence

---

### Recent Tutors Widget

**Shows:** Last 3 tutors you've booked (for quick rebooking)

**Card Layout:**
```
┌──────────────────────────┐
│ AB  Tutor Name           │
│     ⭐ 4.8/5            │
│                          │
│  [Book Again]           │
└──────────────────────────┘
```

**Avatar:**
- Colored circle with initials (A = first initial, B = last initial)
- Example: John Smith = "JS"

**Rating:**
- Star rating from all students who booked this tutor
- Higher = better tutor

**Book Again Button:**
- Quickly start a new booking with this tutor
- Saves time if you liked this tutor

**View All Tutors Link:**
- Browse all available tutors on platform
- Filter by subject, rating, availability

---

## Key Metrics Explained

### 📅 Upcoming Sessions
**What it means:** How many lessons do you have scheduled in the next 7 days?
- 0 = No lessons this week
- 1+ = You have at least one lesson coming up
- Action: Book more if you want more learning

### ⏳ Pending Requests
**What it means:** How many booking requests are waiting for tutor response?
- 0 = All your requests have been answered
- 1+ = Some tutors haven't responded yet
- Action: Be patient or try booking someone else

### ✅ Completed Sessions
**What it means:** How many lessons have you finished?
- 0 = Haven't completed any sessions yet
- 1+ = You've finished sessions with feedback from tutors
- Action: Keep learning, track your progress

### ⭐ Feedback Given
**What it means:** How many times have you left feedback for tutors?
- 0 = Haven't rated any tutors yet
- 1+ = You've provided feedback (helps other students!)
- Action: Always leave feedback after sessions

---

## How to Use Each Feature

### Viewing Your Upcoming Lesson
1. Look at **Scheduled Sessions** widget
2. Find your next lesson in the blue cards
3. See tutor name, subject, and exact date/time
4. Add to your calendar

### Following Up on Pending Requests
1. Check **Pending Requests** widget
2. See yellow cards of waiting bookings
3. If taking too long, book a different tutor
4. Or contact tutor directly

### Learning from Completed Sessions
1. Scroll to **Completed Sessions** widget
2. Find past lessons in green cards
3. Read tutor's feedback and star rating
4. Implement suggestions for next lesson

### Booking Your Favorite Tutor Again
1. Look at **Recent Tutors** widget (right sidebar)
2. Find tutor you liked
3. Click [Book Again]
4. Fill in new lesson details
5. Wait for their response

### Tracking Your Learning Progress
1. Look at **Sessions Per Month** chart (line)
2. See 6-month history of your learning
3. Trend shows if you're getting more active lessons
4. Peak activity = you're really engaged in learning!

---

## Status Badges Meaning

### Pending
```
[Pending] - Yellow badge
```
- Tutor hasn't responded yet
- You're waiting for their decision
- May take 24-48 hours typically

### Scheduled  
```
[Scheduled] - Blue badge
```
- Tutor accepted your request
- Lesson is booked and confirmed
- Check date/time to prepare

### Completed
```
[Completed] - Green badge
```
- Lesson is finished
- You should leave feedback
- Tutor will rate your performance

### Cancelled
```
[Cancelled] - Red badge (in chart only)
```
- Booking was cancelled
- Either you or tutor cancelled
- Try booking again if needed

---

## Tips for Success

### Booking Efficiently
1. **Be specific:** Write clear notes about what you need help with
2. **Be flexible:** Offer multiple time slots if possible
3. **Check ratings:** Look at recent tutors' ratings in sidebar
4. **Communicate:** If tutor doesn't respond, try someone else

### Learning Effectively
1. **Show up on time:** Tutors appreciate punctuality
2. **Prepare materials:** Have your questions ready
3. **Take notes:** Write down key points during lesson
4. **Leave feedback:** Always rate tutor after session

### Tracking Progress
1. **Check chart monthly:** See if your sessions are trending up
2. **Read feedback:** Tutor comments help you improve
3. **Book consistently:** Regular sessions = better learning
4. **Try different tutors:** Each brings unique teaching style

---

## Quick Reference

| Action | Where to Click |
|--------|---|
| Book a new tutor | Green "Book a Tutor" button at top |
| Browse all tutors | Blue "Browse Tutors" button at top |
| See calendar | Purple "View Calendar" button at top |
| Book recent tutor | [Book Again] button in Recent Tutors widget |
| See all tutors | [View All Tutors] button in Recent Tutors |
| Check metrics | Look at 4 cards at top |
| See learning trend | Look at Monthly Sessions chart |
| Check session breakdown | Look at Status breakdown doughnut |

---

## Performance Tracking Over Time

### Monthly Chart Guide
```
             Sessions
                  3 │         ╭─╮
                  2 │      ╭─╮│ │╭─╮
                  1 │   ╭─╮│ ││ ││ │╭─╮
                  0 │═══╯ ╰╯ ╰╯ ╰╯ ╰╯
                    └─────────────────────
                      May Jun Jul Aug Sep Oct
```
- **Rising slope** = You're booking more lessons (great!)
- **Flat line** = Consistent learning pace
- **Dips** = Maybe busier months or break

### Status Chart Guide
```
Pending 10%    ▰░░░░░░░░░
Scheduled 30%  ▰▰▰░░░░░░░
Completed 50%  ▰▰▰▰▰░░░░░
Cancelled 10%  ▰░░░░░░░░░

Ideal: High completed%, low pending/cancelled%
```

---

## Frequently Used Flows

### Flow 1: Book a New Tutor
```
Click [Book a Tutor] → 
Select subject → 
Select tutor → 
Choose time slot → 
Add notes → 
Submit request → 
Wait for response in "Pending Requests" →
Once accepted, appears in "Scheduled Sessions"
```

### Flow 2: Attend a Scheduled Session
```
See in "Scheduled Sessions" →
Note the date/time →
Log in at time specified →
Meet with tutor →
Session completes →
Appears in "Completed Sessions" →
Read tutor's feedback
```

### Flow 3: Book Same Tutor Again
```
Go to "Recent Tutors" (right sidebar) →
Find tutor you liked →
Click [Book Again] →
Select new time →
Wait for acceptance
```

---

## Mobile View Notes

On mobile phones:
- 1 column layout (cards stack vertically)
- Metric cards remain 4 in a row (slide to see all)
- Charts automatically resize to fit screen
- Buttons remain full-width and touch-friendly
- Sidebar moves to bottom
- All functionality works perfectly

---

## Common Questions

**Q: Why is my request pending?**
A: Tutor hasn't responded yet. They may be busy or offline. Try another tutor if waiting too long.

**Q: Can I change a scheduled session time?**
A: Contact the tutor directly to reschedule. Or cancel and book a new time.

**Q: What if a tutor cancels my lesson?**
A: It appears in the chart as cancelled. You can immediately book another tutor.

**Q: How do I rate a tutor?**
A: After session completes, feedback form appears. Stars + optional comment.

**Q: What's a good completion rate?**
A: 70%+ is great! Means most of your bookings happen.

---

**Created:** November 20, 2025  
**For:** Student Dashboard (Phase 8B)  
**Version:** 1.0  
