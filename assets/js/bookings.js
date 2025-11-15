document.addEventListener("DOMContentLoaded", () => {

    const returnedDate = document.getElementById("returned_date");
    const endDate = document.getElementById("end_date").value;
    const deposit = parseFloat(document.getElementById("deposit").value);

    const lateDaysInput = document.getElementById("late_days");
    const lateFeeInput = document.getElementById("late_fee");
    const refundInput = document.getElementById("refund");
    const statusSelect = document.getElementById("status");

    const LATE_FEE_PER_DAY = 500;

    function calculate() {
        let lateDays = 0;
        let lateFee = 0;
        let refund = deposit;

        if (returnedDate.value) {
            const r = new Date(returnedDate.value);
            const e = new Date(endDate);

            const diff = Math.ceil((r - e) / (1000 * 60 * 60 * 24));
            lateDays = diff > 0 ? diff : 0;

            lateFee = lateDays * LATE_FEE_PER_DAY;
            refund = Math.max(0, deposit - lateFee);

            statusSelect.value = "Completed";
        } else {
            const today = new Date();
            const e = new Date(endDate);

            const diff = Math.ceil((today - e) / (1000 * 60 * 60 * 24));
            lateDays = diff > 0 ? diff : 0;

            if (lateDays > 0) {
                statusSelect.value = "Overdue";
            } else {
                statusSelect.value = "Active";
            }
        }

        lateDaysInput.value = lateDays;
        lateFeeInput.value = lateFee;
        refundInput.value = refund;
    }

    returnedDate.addEventListener("change", calculate);

    calculate(); // initial load
});
