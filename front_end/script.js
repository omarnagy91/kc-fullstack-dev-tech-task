// Fetch categories from the API and organize them into a tree structure
fetch('http://api.cc.localhost/categories')
  .then(response => response.json())
  .then(categories => {
    const categoriesTree = buildCategoriesTree(categories);
    const categoriesList = document.querySelector('.categories-list');
    renderCategories(categoriesTree, categoriesList);
  })
  .catch(error => {
    console.error('Error fetching categories:', error);
  });

// Fetch courses from the API
fetch('http://api.cc.localhost/courses')
  .then(response => response.json())
  .then(courses => {
    const coursesGrid = document.querySelector('.courses-grid');
    renderCourses(courses, coursesGrid);
  })
  .catch(error => {
    console.error('Error fetching courses:', error);
  });

// Build a tree structure from flat category data and sum course counts
function buildCategoriesTree(categories) {
  let tree = [];
  let childrenOf = {};
  categories.forEach(category => {
    childrenOf[category.id] = childrenOf[category.id] || [];
    category.subcategories = childrenOf[category.id];
    category.totalCourses = category.count_of_courses;

    if (category.parent_id !== null) {
      childrenOf[category.parent_id] = childrenOf[category.parent_id] || [];
      childrenOf[category.parent_id].push(category);
    } else {
      tree.push(category);
    }
  });

  function sumCourses(category) {
    category.subcategories.forEach(subcategory => {
      category.totalCourses += sumCourses(subcategory);
    });
    return category.totalCourses;
  }

  tree.forEach(rootCategory => {
    sumCourses(rootCategory);
  });

  return tree;
}

/**
 * Render categories recursively with adjustments for tree view and selection
 * @param {Array} categories - The array of categories
 * @param {HTMLElement} parentElement - The parent element to append the categories to
 * @param {number} [depth=0] - The depth level of the current category
 * @param {string} [parentId=null] - The ID of the parent category
 */
// Render categories recursively with adjustments for tree view and selection
function renderCategories(categories, parentElement, depth = 0) {
  categories.forEach(category => {
    const categoryItem = document.createElement('li');
    categoryItem.classList.add('category-item');
    categoryItem.dataset.categoryId = category.id;
    categoryItem.dataset.depth = depth;

    const categoryBtn = document.createElement('button');
    categoryBtn.classList.add('category-btn');
    categoryBtn.textContent = category.name;
    categoryBtn.style.marginLeft = `${0 * depth}px`; // Indent based on depth
    categoryBtn.addEventListener('click', () => {
      clearSelections();
      categoryItem.classList.add('selected');
      highlightParentCategory(categoryItem);
      renderCoursesByCategory(category.id);
    });

    // Display total count of courses including subcategories if available
    if (category.totalCourses) {
      const countSpan = document.createElement('span');
      countSpan.textContent = ` (${category.totalCourses})`;
      countSpan.classList.add('course-count');
      categoryBtn.appendChild(countSpan);
    }

    categoryItem.appendChild(categoryBtn);
    parentElement.appendChild(categoryItem);

    // Render subcategories recursively
    if (category.subcategories) {
      const subcategoriesList = document.createElement('ul');
      subcategoriesList.classList.add('subcategories-list');
      categoryItem.appendChild(subcategoriesList);
      renderCategories(category.subcategories, subcategoriesList, depth + 1);
    }
  });
}
/**
 * Highlight the parent category of the selected subcategory
 * @param {HTMLElement} selectedCategoryItem - The selected category item element
 */
function highlightParentCategory(selectedCategoryItem) {
  let parentId = selectedCategoryItem.dataset.parentId;
  while (parentId) {
    const parentCategoryItem = document.querySelector(`.category-item[data-category-id="${parentId}"]`);
    if (parentCategoryItem) {
      parentCategoryItem.classList.add('parent-selected');
      parentId = parentCategoryItem.dataset.parentId;
    }
  }
}

/**
 * Clear all selected categories and subcategories
 */
function clearSelections() {
  const selectedCategories = document.querySelectorAll('.category-item.selected');
  selectedCategories.forEach(category => category.classList.remove('selected'));
}

/**
 * Render courses by category
 * @param {string} categoryId - The ID of the selected category
 */
function renderCoursesByCategory(categoryId) {
  const coursesGrid = document.querySelector('.courses-grid');
  coursesGrid.innerHTML = ''; // Clear previous courses

  fetch(`http://api.cc.localhost/courses?category_id=${categoryId}`)
    .then(response => response.json())
    .then(courses => {
      renderCourses(courses, coursesGrid);
      updatePageTitle(categoryId);
    })
    .catch(error => {
      console.error('Error fetching courses:', error);
    });
}

/**
 * Update the page title based on the selected category
 * @param {string} categoryId - The ID of the selected category
 */
function updatePageTitle(categoryId) {
  const pageTitle = document.querySelector('.page-title');

  if (categoryId) {
    fetch(`http://api.cc.localhost/categories/${categoryId}`)
      .then(response => response.json())
      .then(category => {
        pageTitle.textContent = category.name;
      })
      .catch(error => {
        console.error('Error fetching category:', error);
      });
  } else {
    pageTitle.textContent = 'Course Catalog';
  }
}

/**
 * Render courses
 * @param {Array} courses - The array of courses
 * @param {HTMLElement} parentElement - The parent element to append the courses to
 */
function renderCourses(courses, parentElement) {
  courses.forEach(course => {
    const courseCard = document.createElement('div');
    courseCard.classList.add('course-card');

    const courseImage = document.createElement('img');
    courseImage.classList.add('course-image');
    courseImage.src = course.preview;
    courseImage.alt = course.name;
    courseCard.appendChild(courseImage);

    const courseCategory = document.createElement('div');
    courseCategory.classList.add('course-category');
    courseCategory.textContent = course.main_category_name;
    courseCard.appendChild(courseCategory);

    const courseDetails = document.createElement('div');
    courseDetails.classList.add('course-details');
    courseCard.appendChild(courseDetails);

    const courseName = document.createElement('h3');
    courseName.classList.add('course-name');
    courseName.textContent = course.name;
    courseDetails.appendChild(courseName);

    const courseDescription = document.createElement('p');
    courseDescription.classList.add('course-description');
    courseDescription.textContent = course.description;
    courseDetails.appendChild(courseDescription);

    parentElement.appendChild(courseCard);
  });
}
