-- Schools (for future multi-school scaling)
CREATE TABLE schools (
  id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  name TEXT NOT NULL,
  location TEXT,
  created_at TIMESTAMP DEFAULT NOW()
);

-- Users (all roles in one table)
CREATE TABLE users (
  id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  school_id UUID REFERENCES schools(id),
  full_name TEXT NOT NULL,
  email TEXT UNIQUE NOT NULL,
  password_hash TEXT NOT NULL,        -- PHP password_hash()
  role TEXT CHECK (role IN ('teacher', 'principal', 'admin')) NOT NULL,
  weekly_hour_cap INTEGER DEFAULT 8,  -- Only relevant for teachers; set by principal
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT NOW()
);

-- Classes
CREATE TABLE classes (
  id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  school_id UUID REFERENCES schools(id),
  name TEXT NOT NULL,                 -- e.g. "Form 4A", "Upper Six Science"
  hourly_rate INTEGER NOT NULL,       -- 500 or 700 XAF
  created_at TIMESTAMP DEFAULT NOW()
);

-- Session Logs (core table)
CREATE TABLE session_logs (
  id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  teacher_id UUID REFERENCES users(id),
  class_id UUID REFERENCES classes(id),
  subject TEXT NOT NULL,              -- e.g. "Mathematics"
  hours_taught NUMERIC(4,1) NOT NULL, -- e.g. 2.0 or 1.5
  session_date DATE NOT NULL,
  logged_at TIMESTAMP DEFAULT NOW(),
  week_number INTEGER,                -- Auto-calculated: EXTRACT(WEEK FROM session_date)
  year INTEGER,                       -- Auto-calculated: EXTRACT(YEAR FROM session_date)
  amount_xaf INTEGER,                 -- Auto-calculated: hours_taught × class.hourly_rate
  status TEXT DEFAULT 'normal'        -- 'normal' | 'blocked'
);

-- Monthly Payroll Snapshots
CREATE TABLE payroll_summaries (
  id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  teacher_id UUID REFERENCES users(id),
  school_id UUID REFERENCES schools(id),
  month INTEGER NOT NULL,             -- 1-12
  year INTEGER NOT NULL,
  total_hours NUMERIC(6,1),
  total_amount_xaf INTEGER,
  generated_at TIMESTAMP DEFAULT NOW()
);