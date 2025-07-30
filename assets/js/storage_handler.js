/**
 * File: storage_handler.js
 * Deskripsi: Skrip ini menangani penyimpanan dan pemuatan data ujian
 * ke localStorage browser secara otomatis dan permanen.
 * Data tidak akan dihapus secara otomatis.
 */

document.addEventListener("DOMContentLoaded", () => {
  // Kunci yang akan digunakan di localStorage
  const USER_DATA_KEY = "examUserData";
  const QUESTIONS_KEY = "examQuestionsText";

  /**
   * Bagian ini berjalan di halaman index.php
   * untuk menyimpan dan memuat data pengguna.
   */
  const formUserInfo = document.querySelector('form[action="input_soal.php"]');
  if (formUserInfo) {
    const nimInput = document.getElementById("nim"); // <-- Tambahkan ini
    const namaInput = document.getElementById("nama_lengkap");
    const kelasInput = document.getElementById("kelas");
    const matkulInput = document.getElementById("mata_kuliah");
    const durasiSelect = document.getElementById("durasi");

    // Fungsi untuk memuat data pengguna dari localStorage
    const loadUserData = () => {
      const savedData = localStorage.getItem(USER_DATA_KEY);
      if (savedData) {
        try {
          const userData = JSON.parse(savedData);
          nimInput.value = userData.nim || ""; // <-- Tambahkan ini
          namaInput.value = userData.nama || "";
          kelasInput.value = userData.kelas || "";
          matkulInput.value = userData.matkul || "";
          durasiSelect.value = userData.durasi || "";
        } catch (e) {
          console.error(
            "Gagal mem-parsing data pengguna dari localStorage:",
            e
          );
          localStorage.removeItem(USER_DATA_KEY);
        }
      }
    };

    // Fungsi untuk menyimpan data pengguna ke localStorage
    const saveUserData = () => {
      const userData = {
        nim: nimInput.value, // <-- Tambahkan ini
        nama: namaInput.value,
        kelas: kelasInput.value,
        matkul: matkulInput.value,
        durasi: durasiSelect.value,
      };
      localStorage.setItem(USER_DATA_KEY, JSON.stringify(userData));
    };

    // Muat data saat halaman dibuka
    loadUserData();

    // Tambahkan event listener untuk menyimpan data setiap kali ada perubahan
    nimInput.addEventListener("input", saveUserData); // <-- Tambahkan ini
    namaInput.addEventListener("input", saveUserData);
    kelasInput.addEventListener("input", saveUserData);
    matkulInput.addEventListener("input", saveUserData);
    durasiSelect.addEventListener("change", saveUserData);
  }

  /**
   * Bagian ini berjalan di halaman input_soal.php
   * untuk menyimpan dan memuat teks soal.
   */
  const soalTextarea = document.getElementById("soal_text");
  if (soalTextarea) {
    // Fungsi untuk memuat teks soal dari localStorage
    const loadQuestions = () => {
      const savedQuestions = localStorage.getItem(QUESTIONS_KEY);
      if (savedQuestions) {
        soalTextarea.value = savedQuestions;
      }
    };

    // Fungsi untuk menyimpan teks soal ke localStorage
    const saveQuestions = () => {
      localStorage.setItem(QUESTIONS_KEY, soalTextarea.value);
    };

    // Muat soal saat halaman dibuka
    loadQuestions();

    // Tambahkan event listener untuk menyimpan soal setiap kali ada perubahan
    soalTextarea.addEventListener("input", saveQuestions);
  }
});
