# **📝 PHP Dynamic Exam System**

A secure, dynamic, and feature-rich online examination system built with vanilla PHP and JavaScript. This project allows instructors to create on-the-fly exams by simply pasting formatted questions, while providing a secure, full-screen, anti-cheating environment for students.


## **🚀 Key Features**

This isn't just a simple exam form. It's a comprehensive system packed with features for both administrators and examinees.

* **👨‍💻 Dynamic Question Loading:** No database needed\! Simply copy and paste formatted questions into a textarea to generate a complete exam instantly.  
* **🔢 Unlimited & Unordered Questions:** The system uses unique internal IDs, allowing for unlimited questions with any numbering format, including duplicates.  
* **🎲 Randomized Questions & Options:** Both the order of the questions and the multiple-choice options (A, B, C, etc.) are shuffled for each session to prevent cheating.  
* **⏱️ Real-time Countdown Timer:** A persistent timer tracks the exam duration and automatically submits the answers when the time runs out.  
* **🔒 Robust Anti-Cheating Security Suite:**  
  * **Desktop Only:** Automatically blocks access from mobile and tablet devices.  
  * **Forced Fullscreen Mode:** The exam starts in and enforces a fullscreen environment.  
  * **Tab & Window Switch Detection:** Detects if a user leaves the exam window (using Alt+Tab, Windows Key, etc.) and issues warnings before terminating the session.  
  * **Keyboard Lockdowns:** Disables Tab, Alt, Windows Key, and right-click functionalities.  
  * **Screenshot & DevTools Blocking:** Detects and penalizes attempts to take screenshots or open developer tools.  
  * **Copy-Paste Disabled:** Prevents users from copying question text.  
* **📊 Detailed Result Analysis:**  
  * Provides an instant score, grade (A, B, C, D), and pass/fail status.  
  * Separates correct and incorrect answers for easy review.  
  * Shows the user's incorrect answer alongside the correct one for learning purposes.  
  * Generates a performance evaluation and personalized recommendations.  
* **💾 Persistent Progress with Local Storage:** Automatically saves user data (NIM, name, class) and the pasted questions in the browser's local storage. If the page is refreshed, the data is reloaded, preventing loss of progress.  
* **📄 PDF Proof of Examination:** Users can view and download a clean, well-formatted PDF certificate as proof of exam completion, complete with dynamic data like NIM and course name.

## **🛠️ Tech Stack**

The project is built with a focus on simplicity and accessibility, using fundamental web technologies without heavy frameworks.

* **Backend:** PHP (for session management and server-side logic)  
* **Frontend:** HTML5, CSS3, Vanilla JavaScript (ES6)  
* **Libraries:**  
  * [SweetAlert2](https://sweetalert2.github.io/): For beautiful and responsive pop-up notifications.  
  * [html2canvas](https://html2canvas.hertzen.com/): For capturing the exam proof for PDF generation.  
  * [jsPDF](https://github.com/parallax/jsPDF): For generating the downloadable PDF certificate.

## **📂 Project Structure**

The file structure is organized to be intuitive and easy to navigate.

/exam-online/  
├── assets/  
│   ├── css/  
│   │   ├── ujian.css         \# Main styles for the exam page  
│   │   └── ...               \# Other CSS files  
│   ├── js/  
│   │   └── storage\_handler.js \# Logic for local storage  
│   └── img/  
│       ├── headerpdf.png     \# Header image for the certificate  
│       └── qrpdf.png         \# QR code image for the certificate  
│  
├── includes/  
│   └── parser.php            \# The core engine for parsing question text  
│  
├── index.php                 \# Step 1: User data input  
├── input\_soal.php            \# Step 2: Pasting the exam questions  
├── ujian.php                 \# Step 3: The main exam environment  
├── hasil.php                 \# Step 4: Results, analysis, and evaluation  
└── dokumen.php               \# The clean HTML template for the PDF certificate

## **⚙️ Setup and Installation**

To run this project on your local machine, follow these simple steps.

**Prerequisites:**

* A local server environment like [XAMPP](https://www.apachefriends.org/index.html) or WAMP.

**Installation:**

1. **Clone the repository:**  
   git clone https://github.com/ibnusna/exam-online.git

2. Move to server directory:  
   Move the cloned php-exam-system folder into the htdocs directory of your XAMPP installation (e.g., C:/xampp/htdocs/).  
3. Start your server:  
   Open the XAMPP Control Panel and start the Apache service.  
4. Access the project:  
   Open your web browser and navigate to http://localhost/exam-online/ The index.php page should load.

## **❓ How It Works**

The application follows a simple, four-step workflow:

1. **index.php**: The user enters their personal data (NIM, Name, Class, Course) and selects the exam duration. This data is saved to the PHP session and also to the browser's local storage.  
2. **input\_soal.php**: The user pastes the entire set of questions into a large textarea. The text must follow a specific format. This text is also saved to local storage.  
3. **ujian.php**: When the form is submitted, the raw text of the questions is sent to ujian.php. The PHP script uses the parser.php engine to convert the text into a structured array. The question order and option order are shuffled. The user then takes the exam in a secure, fullscreen environment.  
4. **hasil.php**: After submitting, the answers are processed. This page displays a comprehensive analysis, including score, grade, and a detailed breakdown of correct and incorrect answers. From here, the user can also view and download their PDF proof of completion.

## **📋 Question Format**

The parser is designed to be flexible but requires a consistent format for each question block.

* Each question must start with a number followed by a period (e.g., 1., 20.).  
* Multiple-choice options must start with an uppercase letter (A-E) followed by a period.  
* The correct answer must be specified at the end of the block with the format Kunci Jawaban: B.  
* The parser **does not** require blank lines between questions.

**Correct Example:**

1\. What is the capital of Indonesia?  
A. Bandung  
B. Surabaya  
C. Jakarta  
D. Medan  
Kunci Jawaban: C  
2\. Which of the following is a PHP framework?  
A. React  
B. Django  
C. Laravel  
D. Express  
Kunci Jawaban: C

## **🤝 Contributing**

Contributions are welcome\! If you have ideas for new features or find a bug, please feel free to open an issue or submit a pull request.

1. Fork the Project  
2. Create your Feature Branch (git checkout \-b feature/AmazingFeature)  
3. Commit your Changes (git commit \-m 'Add some AmazingFeature')  
4. Push to the Branch (git push origin feature/AmazingFeature)  
5. Open a Pull Request

## **📜 License**

This project is licensed under the MIT License. See the LICENSE file for more details.
