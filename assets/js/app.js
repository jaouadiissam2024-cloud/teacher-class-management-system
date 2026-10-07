document.addEventListener("DOMContentLoaded", function () {
    const deleteForms = document.querySelectorAll(".inline-delete");

    deleteForms.forEach(form => {
        form.addEventListener("submit", function (e) {
            const ok = confirm("هل تريد حذف هذا الفصل؟");
            if (!ok) {
                e.preventDefault();
            }
        });
    });
});
