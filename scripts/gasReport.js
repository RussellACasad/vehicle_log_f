// Edit Gas modal
try {
    const openGas = document.getElementById("open-gas");
    const closeGas = document.getElementById("close-gas");
    const gasModal = document.getElementById("edit-gas");

    openGas.addEventListener("click", () => {
        gasModal.classList.add("modal-show");
    });

    closeGas.addEventListener("click", () => {
        gasModal.classList.remove("modal-show");
    });
} catch (error) {
    console.log("Edit Gas Modal failed to initialize");
}

try {
    const notif = document.getElementById("notification");

    if (isShowingMessage) {
        notif.classList.add("notification-show");

        setTimeout(() => {
            notif.classList.remove("notification-show");
        }, 3500);
    };
}
catch (error)
{
    console.log("No message.");
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