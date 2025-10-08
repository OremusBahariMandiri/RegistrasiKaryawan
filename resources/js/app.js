import './bootstrap';

// This file will be processed by Vite
// We'll ensure jQuery and Bootstrap are properly loaded

// Import jQuery
import $ from 'jquery';
window.$ = window.jQuery = $;

// Import Bootstrap
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

// Import DataTables (if needed)
import 'datatables.net';
import 'datatables.net-bs5';
import 'datatables.net-responsive';
import 'datatables.net-responsive-bs5';

// Import the main CSS file
import '../css/app.css';

// Import the sidebar CSS explicitly
// import '../css/sidebar.css';

// Flag untuk mencegah inisialisasi ganda
if (window.appInitialized) {
    console.log("App already initialized, skipping");
} else {
    window.appInitialized = true;

    // Initialize components when document is ready
    document.addEventListener('DOMContentLoaded', function () {
        console.log("Initializing app.js components");

        // Initialize DataTables
        initDataTables();

        // Initialize Bootstrap components
        initBootstrapComponents();

        // Initialize delete confirmation
        initDeleteConfirmation();

        // Initialize sidebar functionality
        initSidebar();

        // Auto-hide alerts after 5 seconds
        initAutoHideAlerts();
    });

    // Function to initialize DataTables
    function initDataTables() {
        console.log("Initializing DataTables");
        if ($.fn.DataTable) {
            $('.data-table').each(function () {
                // Check if table is already initialized
                if (!$.fn.DataTable.isDataTable(this)) {
                    // Initialize DataTable only if not already initialized
                    console.log("Initializing table:", this.id);
                    $(this).DataTable({
                        responsive: true,
                        // The language is now set globally in the layout file
                        columnDefs: [
                            {
                                responsivePriority: 1,
                                targets: [0, 1, -1] // Priority on first, second and last column
                            },
                            {
                                orderable: false,
                                targets: [-1] // Last column (actions) not sortable
                            }
                        ]
                    });
                } else {
                    console.log("Table already initialized:", this.id);
                }
            });
        }
    }

    // Function to initialize Bootstrap components
    function initBootstrapComponents() {
        console.log("Initializing Bootstrap components");

        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Initialize popovers
        const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        popoverTriggerList.forEach(function (popoverTriggerEl) {
            new bootstrap.Popover(popoverTriggerEl);
        });
    }

    // Function to initialize delete confirmation
    // Di file app.js - Function untuk initialize delete confirmation
    function initDeleteConfirmation() {
        console.log("Initializing delete confirmation");

        // GUNAKAN EVENT DELEGATION - ini akan bekerja untuk semua halaman DataTables
        $(document).on('click', '.delete-confirm', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const id = $(this).data('id');
            const name = $(this).data('name');
            const url = $(this).data('url') || $(this).data('route');

            console.log("Delete confirmation clicked for:", name);

            // Set item name in modal
            $('#itemNameToDelete').text(name);
            // Untuk halaman jenis dokumen, gunakan ID yang sesuai
            $('#jenisNameToDelete').text(name);

            // Set form action URL
            $('#deleteForm').attr('action', url);

            // Show modal
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
            deleteModal.show();
        });
    }

    // Function to initialize sidebar functionality

    // Function to auto-hide alerts
    function initAutoHideAlerts() {
        // Auto-hide alerts after 5 seconds
        setTimeout(function () {
            $(".alert").fadeOut("slow");
        }, 5000);
    }
}