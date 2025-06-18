const openAdd = document.getElementById("open-add-vehicle");
const closeAdd = document.getElementById("close-add-vehicle");
const addModal = document.getElementById("add-vehicle");

// Add modal
try {
    openAdd.addEventListener("click", () => {
        addModal.classList.add("modal-show");
    });

    closeAdd.addEventListener("click", () => {
        addModal.classList.remove("modal-show");
    });
} catch (e) {

}


try {
    // Notification
    const notif = document.getElementById("notification");

    if (isShowingMessage) {
        notif.classList.add("notification-show");

        setTimeout(() => {
            notif.classList.remove("notification-show");
        }, 3500);
    };
}
catch (e) {

}

