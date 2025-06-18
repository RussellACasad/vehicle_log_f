try {
    const openUser = document.getElementById("open-add-user");
    const closeUser = document.getElementById("close-add-user");
    const userModal = document.getElementById("add-user");

    openUser.addEventListener("click", () => {
        userModal.classList.add("modal-show");
    });

    closeUser.addEventListener("click", () => {
        userModal.classList.remove("modal-show");
    });
} catch (error) {
    console.log("Failed User Modal Initialization")
}

const deleteModal = document.getElementById("delete-modal");
const closeDelete = document.getElementById("close-delete");

closeDelete.addEventListener("click", () => {
    deleteModal.classList.remove("modal-show");
});

function showDeleteModal(name, id)
{
    const modalTitle = document.getElementById("delete-modal-title"); 
    const deleteID = document.getElementById("deleteID"); 

    modalTitle.innerHTML = `Delete ${name}?`; 
    deleteID.value = id; 

    deleteModal.classList.add("modal-show"); 
}