try {
    // Edit Maintenence modal
    const openMaint = document.getElementById("open-maint");
    const closeMaint = document.getElementById("close-maint");
    const maintModal = document.getElementById("edit-maint");

    openMaint.addEventListener("click", () => {
        maintModal.classList.add("modal-show");
    });

    closeMaint.addEventListener("click", () => {
        maintModal.classList.remove("modal-show");
    });
} catch (e) {

}

try {
    const openDelete = document.getElementById("open-delete");
    const closeDelete = document.getElementById("close-delete");
    const deleteModal = document.getElementById("delete-modal");

    openDelete.addEventListener("click", () => {
        deleteModal.classList.add("modal-show");
    });

    closeDelete.addEventListener("click", () => {
        deleteModal.classList.remove("modal-show");
    });
} catch (error) {
    console.log("FAIL")
}

try {
    const notif = document.getElementById("notification");

    if (isShowingMessage) {
        notif.classList.add("notification-show");

        setTimeout(() => {
            notif.classList.remove("notification-show");
        }, 3500);
    };
} catch (e) {

}