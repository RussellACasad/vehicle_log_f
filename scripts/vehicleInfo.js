// Edit Modal
const openEdit = document.getElementById("open-edit");
const closeEdit = document.getElementById("close-edit");
const editModal = document.getElementById("edit-vehicle");

openEdit.addEventListener("click", () => {
    editModal.classList.add("modal-show");
});

closeEdit.addEventListener("click", () => {
    editModal.classList.remove("modal-show");
});

// Change Status Modal

const openStatus = document.getElementById("open-status");
const closeStatus = document.getElementById("close-status");
const editStatusModal = document.getElementById("edit-status");

openStatus.addEventListener("click", () => {
    editStatusModal.classList.add("modal-show");
});

closeStatus.addEventListener("click", () => {
    editStatusModal.classList.remove("modal-show");
});

// Add Gas Modal

const openGas = document.getElementById("open-gas");
const closeGas = document.getElementById("close-gas");
const gasModal = document.getElementById("add-gas");

openGas.addEventListener("click", () => {
    gasModal.classList.add("modal-show");
});

closeGas.addEventListener("click", () => {
    gasModal.classList.remove("modal-show");
});

// Add Maint Modal

const openMaint = document.getElementById("open-maint");
const closeMaint = document.getElementById("close-maint");
const maintModal = document.getElementById("add-maint");

openMaint.addEventListener("click", () => {
    maintModal.classList.add("modal-show");
});

closeMaint.addEventListener("click", () => {
    maintModal.classList.remove("modal-show");
});

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

try {
    const openSched = document.getElementById("open-sched");
    const closeSched = document.getElementById("close-sched");
    const schedModal = document.getElementById("sched-maint");

    openSched.addEventListener("click", () => {
        schedModal.classList.add("modal-show");
    });

    closeSched.addEventListener("click", () => {
        schedModal.classList.remove("modal-show");
    });
} catch (error) {
    console.log("FAIL")
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
