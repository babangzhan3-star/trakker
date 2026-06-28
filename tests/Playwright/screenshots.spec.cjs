// @ts-check
const { test, expect } = require('@playwright/test');
const path = require('path');
const fs = require('fs');

const SCREENSHOT_DIR = path.resolve(__dirname, '..', 'docs', 'screenshots');
const BASE_URL = 'http://localhost:8000';

// Pastikan folder screenshot ada
if (!fs.existsSync(SCREENSHOT_DIR)) {
  fs.mkdirSync(SCREENSHOT_DIR, { recursive: true });
}

/**
 * Ambil full-page screenshot dan simpan.
 */
async function screenshot(page, name) {
  const filePath = path.join(SCREENSHOT_DIR, `${name}.png`);
  await page.screenshot({ path: filePath, fullPage: true });
  console.log(`  ✔ Screenshot saved: ${name}.png`);
  return filePath;
}

/**
 * Tunggu hingga halaman benar-benar siap, lalu tunggu sebentar untuk render.
 */
async function waitForPageReady(page) {
  await page.waitForLoadState('networkidle');
  // Tunggu rendering selesai (font, gambar, dll)
  await page.waitForTimeout(1000);
}

test.describe('📸 Dokumentasi Visual Aplikasi TaskFlow', () => {

  test.beforeAll(async ({ browser }) => {
    // Hanya log bahwa kita mulai
    console.log('Memulai sesi dokumentasi visual...');
  });

  // ─── HALAMAN PUBLIK ─────────────────────────────────

  test('01 - Halaman Welcome (/)', async ({ page }) => {
    await page.goto(`${BASE_URL}/`, { waitUntil: 'networkidle' });
    await page.waitForTimeout(500);
    await screenshot(page, 'welcome');
  });

  test('02 - Halaman Login (/login)', async ({ page }) => {
    await page.goto(`${BASE_URL}/login`, { waitUntil: 'networkidle' });
    await page.waitForTimeout(500);
    await screenshot(page, 'login');
  });

  test('03 - Halaman Forgot Password (/forgot-password)', async ({ page }) => {
    await page.goto(`${BASE_URL}/forgot-password`, { waitUntil: 'networkidle' });
    await page.waitForTimeout(500);
    await screenshot(page, 'forgot-password');
  });

  // ─── LOGIN ─────────────────────────────────────────

  test('04 - Login dan redirect ke dashboard', async ({ page }) => {
    await page.goto(`${BASE_URL}/login`, { waitUntil: 'networkidle' });
    await page.waitForTimeout(300);

    // Isi form login
    await page.fill('input[name="email"]', 'fauzan@student.edu');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');

    // Tunggu redirect ke dashboard
    await page.waitForURL('**/dashboard', { timeout: 10000 });
    await waitForPageReady(page);
    await screenshot(page, 'dashboard');
  });

  // ─── HALAMAN AUTH (setelah login) ─────────────────

  test('05 - Halaman Profile (/profile)', async ({ page }) => {
    await page.goto(`${BASE_URL}/profile`, { waitUntil: 'networkidle' });
    await waitForPageReady(page);
    await screenshot(page, 'profile');
  });

  // ─── COURSES ─────────────────────────────────────

  test('06 - Halaman Mata Kuliah (/courses)', async ({ page }) => {
    await page.goto(`${BASE_URL}/courses`, { waitUntil: 'networkidle' });
    await waitForPageReady(page);
    await screenshot(page, 'courses');
  });

  // ─── LECTURERS ───────────────────────────────────

  test('07 - Halaman Dosen (/lecturers)', async ({ page }) => {
    await page.goto(`${BASE_URL}/lecturers`, { waitUntil: 'networkidle' });
    await waitForPageReady(page);
    await screenshot(page, 'lecturers');
  });

  // ─── PROJECTS ────────────────────────────────────

  test('08 - Halaman Proyek (/projects)', async ({ page }) => {
    await page.goto(`${BASE_URL}/projects`, { waitUntil: 'networkidle' });
    await waitForPageReady(page);
    await screenshot(page, 'projects');
  });

  test('09 - Halaman Buat Proyek (/projects/create)', async ({ page }) => {
    await page.goto(`${BASE_URL}/projects/create`, { waitUntil: 'networkidle' });
    await waitForPageReady(page);
    await screenshot(page, 'projects-create');
  });

  test('10 - Halaman Detail Proyek (/projects/1)', async ({ page }) => {
    await page.goto(`${BASE_URL}/projects/1`, { waitUntil: 'networkidle' });
    await waitForPageReady(page);
    await screenshot(page, 'projects-detail');
  });

  test('11 - Halaman Edit Proyek (/projects/1/edit)', async ({ page }) => {
    await page.goto(`${BASE_URL}/projects/1/edit`, { waitUntil: 'networkidle' });
    await waitForPageReady(page);
    await screenshot(page, 'projects-edit');
  });

  // ─── TASKS ───────────────────────────────────────

  test('12 - Halaman Tugas (/tasks)', async ({ page }) => {
    await page.goto(`${BASE_URL}/tasks`, { waitUntil: 'networkidle' });
    await waitForPageReady(page);
    await screenshot(page, 'tasks');
  });

  test('13 - Halaman Buat Tugas (/tasks/create)', async ({ page }) => {
    await page.goto(`${BASE_URL}/tasks/create`, { waitUntil: 'networkidle' });
    await waitForPageReady(page);
    await screenshot(page, 'tasks-create');
  });

  test('14 - Halaman Edit Tugas (/tasks/1/edit)', async ({ page }) => {
    await page.goto(`${BASE_URL}/tasks/1/edit`, { waitUntil: 'networkidle' });
    await waitForPageReady(page);
    await screenshot(page, 'tasks-edit');
  });

  // ─── REPORTS ─────────────────────────────────────

  test('15 - Halaman Laporan (/reports)', async ({ page }) => {
    await page.goto(`${BASE_URL}/reports`, { waitUntil: 'networkidle' });
    await waitForPageReady(page);
    await screenshot(page, 'reports');
  });

  // ─── ACTIVITIES ─────────────────────────────────

  test('16 - Halaman Riwayat Aktivitas (/activities)', async ({ page }) => {
    await page.goto(`${BASE_URL}/activities`, { waitUntil: 'networkidle' });
    await waitForPageReady(page);
    await screenshot(page, 'activities');
  });

  // ─── USERS ──────────────────────────────────────

  test('17 - Halaman Manajemen Pengguna (/users)', async ({ page }) => {
    await page.goto(`${BASE_URL}/users`, { waitUntil: 'networkidle' });
    await waitForPageReady(page);
    await screenshot(page, 'users');
  });

});
