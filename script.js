
let currentProject = null;

const generatorForm = document.getElementById("generatorForm");

generatorForm.addEventListener("submit", async function(e) {

    e.preventDefault();

    const project = {
        project_name: document.getElementById("project_name").value,
        language: document.getElementById("language").value,
        project_type: document.getElementById("project_type").value,
        description: document.getElementById("description").value
    };

    document.getElementById("loading").style.display = "block";
    document.getElementById("generateBtn").disabled = true;
    document.getElementById("saveBtn").disabled = true;

    try {

        const response = await fetch("generate.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(project)
        });

        const result = await response.json();

        if (!response.ok || !result.success) {
            throw new Error(result.error || "Generation failed.");
        }

        document.getElementById("generated_code").value = result.code;

        currentProject = {
            ...project,
            generated_code: result.code
        };

        document.getElementById("saveBtn").disabled = false;
        document.getElementById("status").textContent =
            "Code generated successfully!";

    } catch (error) {

        document.getElementById("status").textContent = error.message;

    } finally {

        document.getElementById("loading").style.display = "none";
        document.getElementById("generateBtn").disabled = false;
    }
});

function copyCode() {

    const code = document.getElementById("generated_code").value;

    if (!code) {
        alert("Generate code first!");
        return;
    }

    navigator.clipboard.writeText(code)
        .then(() => alert("Code copied successfully!"))
        .catch(() => alert("Unable to copy code."));
}

function downloadCode() {

    const code = document.getElementById("generated_code").value;

    if (!code) {
        alert("Generate code first!");
        return;
    }

    const language = document.getElementById("language").value;

    const extensions = {
        PHP: "php",
        HTML: "html",
        JavaScript: "js"
    };

    const blob = new Blob([code], {
        type: "text/plain"
    });

    const link = document.createElement("a");

    link.href = URL.createObjectURL(blob);
    link.download = "generated_code." + extensions[language];

    link.click();

    URL.revokeObjectURL(link.href);
}

async function saveCode() {

    if (!currentProject) {
        alert("Generate code first!");
        return;
    }

    try {

        const response = await fetch("save_code.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(currentProject)
        });

        const result = await response.json();

        if (!response.ok || !result.success) {
            throw new Error(result.error || "Could not save project.");
        }

        alert("Project saved successfully!");

    } catch (error) {
        alert(error.message);
    }
}
