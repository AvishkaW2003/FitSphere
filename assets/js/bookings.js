// /FitSphere/assets/js/booking.js

document.addEventListener('DOMContentLoaded', function() {
    const returnedDateInput = document.getElementById('returned_date');
    
    // Check if we are on the view/edit page
    if (!returnedDateInput) return; 

    const endDate = document.getElementById('end_date').value;
    const deposit = parseFloat(document.getElementById('deposit').value);
    const lateFeePerDay = parseFloat(document.getElementById('late_fee_per_day').value) || 500; // Default to 500 if hidden field is missing

    const lateDaysInput = document.getElementById('late_days');
    const lateFeeInput = document.getElementById('late_fee');
    const refundInput = document.getElementById('refund');
    const statusSelect = document.getElementById('status');
    const returnButton = document.querySelector('.submit-btn');

    function calculateReturn() {
        const returnedDateValue = returnedDateInput.value;
        if (!returnedDateValue) {
            // If date is cleared, reset calculated fields
            lateDaysInput.value = '';
            lateFeeInput.value = '';
            refundInput.value = deposit.toFixed(2);
            // Don't change status automatically, let the form retain its status unless the user changes it.
            return;
        }

        const endTs = new Date(endDate).getTime();
        const retTs = new Date(returnedDateValue).getTime();
        
        const dayInMilliseconds = 86400000; // 1000 * 60 * 60 * 24

        let lateDays = 0;
        let lateFee = 0;
        let finalRefund = 0;

        if (retTs > endTs) {
            // Calculate difference in days, round up to nearest full day
            let daysDifference = Math.ceil((retTs - endTs) / dayInMilliseconds);
            lateDays = daysDifference;
            lateFee = lateDays * lateFeePerDay;
            finalRefund = Math.max(0, deposit - lateFee);
            statusSelect.value = "Overdue"; // Suggest overdue if late
        } else {
            // Returned on time or early
            lateDays = 0;
            lateFee = 0;
            finalRefund = deposit;
            
        }

        // Update the form fields
        lateDaysInput.value = lateDays;
        lateFeeInput.value = lateFee.toFixed(2);
        refundInput.value = finalRefund.toFixed(2);
        statusSelect.value = "Completed"; // Set status to Completed when a return date is entered
        returnButton.textContent = "PROCESS RETURN";
    }

    // Attach event listeners
    returnedDateInput.addEventListener('change', calculateReturn);

    // Initial calculation if date is pre-filled
    calculateReturn();
});