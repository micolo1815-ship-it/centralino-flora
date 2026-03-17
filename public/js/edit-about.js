  // =============================== Upload Single Image js start here ================================================
  const uploadWrappers = document.querySelectorAll(".upload-image-wrapper");

  uploadWrappers.forEach((wrapper) => {
    const fileInput = wrapper.querySelector("input[type='file']");
    const uploadedImgContainer = wrapper.querySelector(".uploaded-img");
    const imagePreview = uploadedImgContainer.querySelector("img");
    const removeButton = uploadedImgContainer.querySelector(".uploaded-img__remove");
  
    fileInput.addEventListener("change", (e) => {
      if (e.target.files.length) {
        const src = URL.createObjectURL(e.target.files[0]);
        imagePreview.src = src;
        uploadedImgContainer.classList.remove("d-none");
      }
    });
  
    removeButton.addEventListener("click", () => {
      imagePreview.src = "assets/images/user.png"; // fallback image
      uploadedImgContainer.classList.add("d-none");
      fileInput.value = ""; // clear input
    });
  });
  
  // =============================== Upload Single Image js End here ================================================