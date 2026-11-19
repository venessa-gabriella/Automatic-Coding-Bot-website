
<?php
session_start();
include "config/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$id = $_SESSION["user_id"];

$stmt = $conn->prepare(
    "SELECT * FROM projects WHERE user_id=? ORDER BY created_at DESC"
);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
<title>My Projects | CodeGen AI</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>

<header class="header">
<h1>My Generated Projects</h1>
<a href="dashboard.php" class="logout">Back to Dashboard</a>
</header>

<div class="container">

<h2>Saved Source Codes</h2>

<input type="text" id="searchProject"
placeholder="Search your projects...">

<table>
<tr>
<th>Project Name</th>
<th>Language</th>
<th>Project Type</th>
<th>Date Created</th>
<th>Action</th>
</tr>

<?php while ($row = $result->fetch_assoc()) { ?>

<tr>
<td><?php echo htmlspecialchars($row["project_name"]); ?></td>
<td><?php echo htmlspecialchars($row["language"]); ?></td>
<td><?php echo htmlspecialchars($row["project_type"]); ?></td>
<td><?php echo htmlspecialchars($row["created_at"]); ?></td>
<td>
<a href="delete.php?id=<?php echo $row["id"]; ?>"
onclick="return confirm('Delete this project?')">
Delete
</a>
</td>
</tr>

<tr>
<td colspan="5">
<details>
<summary>View Generated Code</summary>
<pre class="saved-code"><?php
echo htmlspecialchars($row["generated_code"]);
?></pre>
</details>
</td>
</tr>

<?php } ?>

</table>

</div>

<script>
document.getElementById("searchProject").addEventListener("input",
function() {
    let search = this.value.toLowerCase();

    document.querySelectorAll("table tr").forEach(function(row) {
        if (row.querySelector("th")) return;

        row.style.display = row.innerText.toLowerCase().includes(search)
            ? ""
            : "none";
    });
});
</script>

</body>
</html>
