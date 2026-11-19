
<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>CodeGen AI Dashboard</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>

<header class="header">
    <h1>CodeGen AI</h1>
    <div>
        Welcome, <?php echo htmlspecialchars($_SESSION["fullname"]); ?>
        <a href="logout.php" class="logout">Logout</a>
    </div>
</header>

<div class="container">

    <h2>Automatic Code Generator</h2>

    <p>Describe the system you want to create.
       Our coding bot will generate starter code for you.</p>

    <form id="generatorForm">

        <label>Project Name</label>
        <input type="text" id="project_name"
        placeholder="Example: Student Management System" required>

        <label>Select Programming Language</label>
        <select id="language" required>
            <option value="PHP">PHP</option>
            <option value="HTML">HTML</option>
            <option value="JavaScript">JavaScript</option>
        </select>

        <label>Select Project Type</label>
        <select id="project_type" required>
            <option value="Student Management">Student Management</option>
            <option value="Library Management">Library Management</option>
            <option value="Employee Management">Employee Management</option>
            <option value="Inventory Management">Inventory Management</option>
            <option value="Login System">Login System</option>
        </select>

        <label>Describe Your Project</label>
        <textarea id="description"
        placeholder="Explain what you want your system to do..."
        required></textarea>

        <button type="submit" id="generateBtn">
            Generate Code
        </button>

    </form>

    <div id="loading" style="display:none;">
        Generating your code. Please wait...
    </div>

    <div class="result-area">
        <h2>Generated Source Code</h2>

        <textarea id="generated_code"
        placeholder="Your generated code will appear here..."
        spellcheck="false"></textarea>

        <div class="actions">
            <button type="button" onclick="copyCode()">
                Copy Code
            </button>

            <button type="button" onclick="downloadCode()">
                Download Code
            </button>

            <button type="button" id="saveBtn" onclick="saveCode()" disabled>
                Save Project
            </button>
        </div>

        <p id="status"></p>
    </div>

    <a href="history.php" class="history-link">
        View My Generated Projects
    </a>

</div>

<script src="assets/script.js"></script>
</body>
</html>
