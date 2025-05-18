const { post } = require("jquery");

// Get modal elements
const modal = document.getElementById("myModal");
const openModalBtn = document.getElementById("openModalBtn");
const closeSpan = document.getElementsByClassName("close")[0];
const cancelBtn = document.getElementById("cancelBtn");
const importBtn = document.getElementById("importBtn");
const csvFileInput = document.getElementById("csvFileInput");
const csvPreview = document.getElementById("csvPreview");

let selectedFile; // will store the file object

// Open modal when button is clicked
openModalBtn.onclick = function () {
  modal.style.display = "block";
};

// Close modal when the "x" is clicked
closeSpan.onclick = function () {
  closeModal();
};

// Close modal when Cancel is clicked
cancelBtn.onclick = function () {
  closeModal();
};

// Close modal if clicking outside the modal content
window.onclick = function (event) {
  if (event.target === modal) {
    closeModal();
  }
};

// Reset and hide modal
function closeModal() {
  modal.style.display = "none";
  csvFileInput.value = "";
  csvPreview.textContent = "No file selected.";
  selectedFile = null;
}

// When the user selects a CSV file, preview its contents
csvFileInput.addEventListener("change", function (e) {
  selectedFile = e.target.files[0];
  if (selectedFile) {
    const reader = new FileReader();
    reader.onload = function (e) {
      csvPreview.textContent = e.target.result;
    };
    reader.readAsText(selectedFile);
  } else {
    csvPreview.textContent = "No file selected.";
  }
});

// When Import button is clicked, send the file to the server via AJAX
importBtn.onclick = function () {
  if (!selectedFile) {
    alert("Please select a CSV file first.");
    return;
  }

  const formData = new FormData();
  formData.append("file", selectedFile);

  fetch("import.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.text())
    .then((result) => {
      alert(result);
      closeModal();
      location.reload();
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("There was an error importing the CSV file.");
    });
};
