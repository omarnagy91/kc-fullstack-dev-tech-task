// Fetch and display categories and courses on DOM content load
document.addEventListener('DOMContentLoaded', function () {
    fetchCategories();
    fetchCourses();
});

// Fetch categories from the API and handle the response
function fetchCategories() {
    fetch('http://api.cc.localhost/categories')
        .then(response => response.json())
        .then(data => displayCategories(data))
        .catch(error => console.error('Error fetching categories:', error));
}

// Display categories in the category tree
function displayCategories(categories) {
    const categoryTree = document.getElementById('category-tree');
    categories.forEach(category => {
        const categoryItem = document.createElement('div');
        categoryItem.textContent = category.name;
        categoryItem.dataset.id = category.id;
        categoryItem.classList.add('category-item');
        categoryTree.appendChild(categoryItem);
    });
}

// Fetch courses from the API, optionally filtered by category ID
function fetchCourses(categoryId = null) {
    let url = 'http://api.cc.localhost/courses';
    if (categoryId) {
        url += `?category_id=${categoryId}`;
    }
    fetch(url)
        .then(response => response.json())
        .then(data => displayCourses(data))
        .catch(error => console.error('Error fetching courses:', error));
}

// Display courses in the course list
function displayCourses(courses) {
    const courseList = document.getElementById('course-list');
    courseList.innerHTML = ''; // Clear existing courses
    courses.forEach(course => {
        const courseCard = document.createElement('div');
        courseCard.innerHTML = `
            <div class="course-card">
                <h3>${course.title}</h3>
                <p>${course.description}</p>
                <div class="main-category">${course.main_category_name}</div>
            </div>
        `;
        courseList.appendChild(courseCard);
    });
}

// Event delegation for category click
document.getElementById('category-tree').addEventListener('click', function (event) {
    if (event.target.classList.contains('category-item')) {
        selectCategory(event.target);
    }
});

// Handle selecting a category and updating the display
function selectCategory(categoryElement) {
    document.getElementById('header-title').textContent = categoryElement.textContent;
    document.querySelectorAll('.category-item').forEach(item => item.classList.remove('selected'));
    categoryElement.classList.add('selected');
    const categoryId = categoryElement.dataset.id;
    fetchCourses(categoryId);
}