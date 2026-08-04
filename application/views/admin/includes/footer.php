    </div> <!-- End Main Content -->

    <!-- Confirmation Modal -->
    <div id="confirmModal" class="custom-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Are you sure?</h3>
                <span class="close-modal" onclick="closeModal('confirmModal')">&times;</span>
            </div>
            <div class="modal-body">
                <p id="confirmMsg">Are you sure you want to delete this item? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm" style="background: var(--gray); color: #fff;" onclick="closeModal('confirmModal')">Cancel</button>
                <a id="confirmBtn" href="#" class="btn btn-sm btn-danger">Delete Now</a>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.datatable').DataTable();
        });

        function showConfirm(url, msg = null) {
            if (msg) document.getElementById('confirmMsg').innerText = msg;
            document.getElementById('confirmBtn').href = url;
            document.getElementById('confirmModal').style.display = 'block';
        }

        function showModal(id) {
            document.getElementById(id).style.display = 'block';
        }

        function closeModal(id) {
            document.getElementById(id).style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.className === 'custom-modal') {
                event.target.style.display = "none";
            }
        }
    </script>
</body>
</html>
