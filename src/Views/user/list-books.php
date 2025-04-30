<style>
    .highlight {
        background-color: #FFEEBA; 
        font-weight: bold;
        animation: popRow 0.6s ease-in-out;
    }
    @keyframes popRow {
        0%   { transform: scale(1); }
        50%  { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
</style>





<div style="display: flex; justify-content: space-between; align-items: center;">
    <h1>List of Books</h1>
    <a href="/user/create-book" style="padding: 8px 12px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 4px;">+ Create New Book</a>
</div>



<div style="text-align: left; margin: 16px 0;">
    <form id="searchForm" onsubmit="return false;" style="display: inline-block;">
        <input type="text" id = "searchInput" name="search" placeholder="Search by ID or Title"
               style="padding: 6px; width: 250px;">
        <button type="button" onclick="filterBooks()" style="padding: 6px 12px;">Search</button>
    </form>
</div>

<div id="booksList"></div>
<?php
$searchTerm = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : '';
?>


<?php if (isset($_GET['success']) && $_GET['success'] == 'book_borrowed'): ?>
    <div style="padding: 10px; background-color: #D4EDDA; color: #155724; margin-bottom: 15px; border: 1px solid #C3E6CB;">
        Book borrowed successfully! Please return within 10 days.
    </div>
  
<?php endif; ?>
<?php if (isset($_GET['error']) && $_GET['error'] == 'already_borrowed'): ?>
    <div style="padding: 10px; background-color: #F8D7DA; color: #721C24; margin-bottom: 15px; border: 1px solid #F5C6CB;">
        This book is already borrowed by someone else.
    </div>
<?php endif; ?>

<table id="booksTable" border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse; margin-top: 16px;">
    <thead style="background-color: #F2F2F2;">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Author</th>
            <th>ISBN</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php if (isset($books) && count($books) > 0): ?>
        <?php
foreach ($books as $book):

    $match = (
        $searchTerm &&
        (stripos($book['id'], $searchTerm) !== false || stripos($book['title'], $searchTerm) !== false)
    );
?>

<tr class="<?php echo $match ? 'highlight' : ''; ?>">
    <td><?php echo htmlspecialchars($book['id']); ?></td>
    <td><?php echo htmlspecialchars($book['title']); ?></td>
    <td><?php echo htmlspecialchars($book['author']); ?></td>
    <td><?php echo htmlspecialchars($book['isbn']); ?></td>
    <td>
        <?php 
       
        if (isset($_SESSION['user']['role_name']) && 
            ($_SESSION['user']['role_name'] === 'librarian' || $_SESSION['user']['role_name'] === 'super_admin')): ?>
           
            <a href="/user/edit-book?id=<?php echo urlencode($book['id']); ?>"
               style="padding: 4px 8px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 4px; margin-right: 4px;">
               Edit
            </a>


            <form class="delete-form" data-book-id="<?php echo $book['id']; ?>" style="display:inline;">
    <button type="button" onclick="confirmDelete(this)" style="padding: 4px 8px; background-color: #DC3545; color: white; border-radius: 4px;">
        Delete
    </button>
</form>

        <?php         
        elseif ($borrowedBook = $bookModel->isBookBorrowed($book['id'])): ?>
            <span>Already Borrowed</span>
        <?php else: ?>
   
            <form class="borrow-form" data-book-id="<?php echo $book['id'];?>">
                <button type="submit" style="padding: 4px 8px; background-color: #28A745; color: white; text-decoration: none; border-radius: 4px;">
                    Borrow
                </button>
            </form>
        <?php endif; ?>
    </td>
</tr>

<?php endforeach; ?>

    <?php else: ?>
        <tr>
            <td colspan="5">No books available.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>

<script>
function filterBooks() {
    const input = document.getElementById("searchInput").value.trim().toLowerCase();
    const table = document.getElementById("booksTable");
    const rows = table.getElementsByTagName("tr");
    for (let i = 1; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName("td");
        if (cells.length > 0) {
            const id = cells[0].innerText.toLowerCase();
            const title = cells[1].innerText.toLowerCase();
            const match = id.includes(input) || title.includes(input);
            if (match || input === "") {
                rows[i].style.display = "";
                rows[i].classList.add("highlight");
            } else {
                rows[i].style.display = "none";
                rows[i].classList.remove("highlight");
            }
        }
    }
}
</script>



<script>
document.querySelectorAll('.borrow-form').forEach(function(form) {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const bookId = this.getAttribute('data-book-id');
        
       
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/user/borrow-book', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function() {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);

                if (response.status === 'success') {
                    alert('Book borrowed successfully!');
                    location.reload(); 
                } else if (response.status === 'already_borrowed') {
                    alert('This book is already borrowed.');
                } else if (response.status === 'unauthenticated') {
                    alert('Please log in to borrow books.');
                    window.location.href = '/login';
                } else {
                    alert('Something went wrong.');
                }
            } else {
                console.error('Request failed');
            }
        };

        xhr.send('book_id=' + encodeURIComponent(bookId));
    });
});
</script>



<script>
function confirmDelete(button) {
    if (!confirm("Are you sure you want to delete this book?")) return;

    const form = button.closest('.delete-form');
    const bookId = form.getAttribute('data-book-id');

    fetch('/user/delete-book', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'id=' + encodeURIComponent(bookId)
    })
    .then(response => {
        if (response.ok) {
           
            form.closest('tr')?.remove(); 
        } else {
            alert('Failed to delete book.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Something went wrong.');
    });
}
</script>


<a href="/logout" class="btn" >Logout</a>