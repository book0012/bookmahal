document.addEventListener("DOMContentLoaded", function () {
    let searchBox = document.getElementById("searchBox");
    let resultsContainer = document.getElementById("results");

    searchBox.addEventListener("input", function () {
        let query = searchBox.value.trim();
        if (query.length === 0) {
            resultsContainer.innerHTML = "<p class='no-results'>Start typing to search...</p>";
            return;
        }

        let xhr = new XMLHttpRequest();
        xhr.open("GET", "search.php?query=" + encodeURIComponent(query) + "&ajax=true", true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                let books = JSON.parse(xhr.responseText);

                // Create a new container to avoid flickering
                let newResults = document.createElement("div");
                newResults.id = "results";

                if (books.length === 0) {
                    newResults.innerHTML = "<p class='no-results'>No results found.</p>";
                } else {
                    books.forEach(book => {
                        let bookDiv = document.createElement("div");
                        bookDiv.classList.add("book");

                        let img = document.createElement("img");
                        img.src = book.cover_image;
                        img.alt = "Book Cover";
                        img.onerror = function () { this.src = 'uploads/default_cover.jpg'; }; // Fallback Image

                        let title = document.createElement("h3");
                        title.textContent = book.title;

                        let author = document.createElement("p");
                        author.textContent = "By " + book.author;

                        bookDiv.appendChild(img);
                        bookDiv.appendChild(title);
                        bookDiv.appendChild(author);
                        newResults.appendChild(bookDiv);
                    });
                }

                // Replace old results with new results without flickering
                resultsContainer.replaceWith(newResults);
                resultsContainer = newResults;
            }
        };
        xhr.send();
    });
});
