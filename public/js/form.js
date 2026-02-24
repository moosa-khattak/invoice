document.addEventListener('DOMContentLoaded', function() {
                // Get local date based on user's timezone, not server timezone
                const today = new Date();
                const localDate = new Date(today.getTime() - (today.getTimezoneOffset() * 60000)).toISOString().split('T')[0];
                
                const dateInput = document.getElementById('invoice-date');
                const dueDateInput = document.getElementById('invoice-due-date');
                
                if (dateInput) {
                    dateInput.min = localDate;
                    
                    // Optional: Make due_date always be >= invoice date
                    dateInput.addEventListener('change', function() {
                        if (dueDateInput) {
                            dueDateInput.min = this.value;
                            if (dueDateInput.value && dueDateInput.value < this.value) {
                                dueDateInput.value = this.value;
                            }
                        }
                    });
                }
                
                if (dueDateInput && dateInput && !dateInput.value) {
                    dueDateInput.min = localDate;
                } else if (dueDateInput && dateInput && dateInput.value) {
                    dueDateInput.min = dateInput.value;
                }
            });