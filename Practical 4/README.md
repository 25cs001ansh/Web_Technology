# Practical 4: JavaScript DOM Manipulation, Event Handling, and UI Interactivity

## 📌 Practical Overview

This practical focuses on enhancing a static HTML/CSS website with
**JavaScript-based DOM manipulation, event handling, and interactive UI
components**.

The project demonstrates how JavaScript can be used to dynamically
modify webpage elements, respond to user actions, manage UI state, and
improve the overall user experience.

------------------------------------------------------------------------

## 🎯 Problem Definition

Add dynamic UI components such as:

-   Collapsible FAQ sections
-   Modal popup
-   Image/content slider
-   Notification banner
-   Hamburger navigation menu
-   Light/Dark theme switcher

These components are implemented using **JavaScript ES6+, HTML5, and
CSS3**.

------------------------------------------------------------------------

## 📁 Project Structure

``` text
Practical-4/
│
├── css/
│   └── CSS stylesheets
│
├── js/
│   └── JavaScript files for DOM manipulation and interactivity
│
├── Image/
│   └── Website images/assets
│
├── index.html
├── about.html
├── admin.html
├── attendance.html
├── contact.html
├── courses.html
├── dashboard.html
├── events.html
├── faq.html
├── feedback.html
├── login.html
├── notices.html
├── profile.html
├── register.html
└── README.md
```

------------------------------------------------------------------------

## 🛠️ Tools and Technologies

-   **HTML5** -- Page structure and content
-   **CSS3** -- Styling, layouts, transitions, and responsive UI
-   **JavaScript ES6+** -- DOM manipulation and event handling
-   **Browser Developer Tools** -- Debugging and console testing
-   **localStorage** -- Saving the user's theme preference

------------------------------------------------------------------------

## ✨ Key Features

### 1. Collapsible FAQ

FAQ items can be expanded and collapsed when the user clicks on a
question.

**JavaScript concepts used:** - DOM selection - Click events - Class
manipulation - Show/hide content

### 2. Modal Popup

A modal window can be displayed dynamically in response to a user
action.

**JavaScript concepts used:** - Event listeners - DOM manipulation - CSS
class toggling - Closing the modal

### 3. Image / Content Slider

The slider allows users to move between different images or content
sections.

**JavaScript concepts used:** - Event handling - Array/index
manipulation - DOM content updates - Basic animation/transition effects

### 4. Notification Banner

A notification message can be displayed to provide useful information to
the user and can be dismissed when required.

### 5. Hamburger Menu

A hamburger button provides a compact navigation menu, particularly
useful for smaller screen sizes.

**JavaScript concepts used:** - Click event listener - Class toggling -
Responsive navigation

### 6. Light/Dark Theme Switcher

Users can switch between light and dark themes.

The selected theme can be stored using `localStorage`, allowing the
preference to be restored when the page is opened again.

Example:

``` javascript
localStorage.setItem("theme", "dark");
```

The saved preference can then be read when the page loads.

------------------------------------------------------------------------

## 🔑 Key Questions / Analysis / Interpretation

### 1. How are DOM elements selected and modified?

JavaScript provides methods such as:

``` javascript
document.getElementById("elementId");
document.querySelector(".className");
document.querySelectorAll(".item");
```

After selecting an element, its content, classes, attributes, or styles
can be modified dynamically.

### 2. Are event listeners attached correctly?

Event listeners are used to execute JavaScript when users interact with
the webpage.

Example:

``` javascript
button.addEventListener("click", function () {
    // Action performed after clicking the button
});
```

### 3. Is localStorage used for remembering the theme choice?

Yes. `localStorage` can store the selected theme in the browser.

Example:

``` javascript
localStorage.setItem("theme", "dark");
```

The value can be retrieved later using:

``` javascript
const theme = localStorage.getItem("theme");
```

### 4. Does interactivity improve usability without breaking accessibility?

Interactive elements should remain easy to understand and operate.
Buttons should have clear labels, navigation should remain usable, and
content should not become inaccessible when JavaScript is enabled.

------------------------------------------------------------------------

## 🎓 Key Skills Addressed

-   DOM selection
-   DOM manipulation
-   Event handling
-   JavaScript ES6+
-   CSS class manipulation
-   `localStorage`
-   Basic animation and transitions
-   Dynamic UI updates
-   Browser console debugging
-   Responsive navigation

------------------------------------------------------------------------

## 💡 Applications

The concepts demonstrated in this practical are useful for:

-   Interactive dashboards
-   Student portals
-   Admin panels
-   Responsive websites
-   Dynamic navigation systems
-   FAQ sections
-   Notification systems
-   User preference management
-   Modern frontend applications

------------------------------------------------------------------------

## ⏱️ Time Requirement

  Activity               Hours
  ------------------ ---------
  Implementation       4 hours
  Total Engagement     6 hours

------------------------------------------------------------------------

## 🧪 Testing and Browser Console

The project should be tested using browser Developer Tools.

Check for:

-   JavaScript errors in the Console
-   Correct button and menu behaviour
-   FAQ expand/collapse functionality
-   Modal opening and closing
-   Slider navigation
-   Theme switching
-   Theme persistence after page refresh
-   Responsive hamburger menu behaviour

------------------------------------------------------------------------

## 📚 Learning Outcome

After completing this practical, students will be able to:

1.  Select and manipulate HTML elements using JavaScript.
2.  Attach and handle browser events.
3.  Create interactive frontend components.
4.  Use CSS classes dynamically from JavaScript.
5.  Store and restore UI preferences using `localStorage`.
6.  Apply basic JavaScript animations and transitions.
7.  Debug frontend behaviour using browser Developer Tools.

------------------------------------------------------------------------

## 🔗 CO Mapping

**CO3, CO4**

------------------------------------------------------------------------

## 🚀 Advanced / Intermediate Extensions

### Intermediate

Add smooth transition effects to:

-   Cards
-   Buttons
-   FAQ sections
-   Menus
-   Modals

Example:

``` css
.card {
    transition: transform 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
}
```

### Advanced

Store user interface preferences using `localStorage` and restore them
automatically when the website loads.

Possible preferences include:

-   Light/Dark theme
-   Sidebar/menu state
-   Selected slider position
-   Other non-sensitive UI preferences

------------------------------------------------------------------------

## 📋 Post Laboratory Work

The final submission should include:

-   Live demo of the website
-   Complete source code
-   README documentation
-   Source-code explanation
-   Demonstration of JavaScript interactions
-   Browser console testing

------------------------------------------------------------------------

## 📝 Evaluation Strategy

The practical can be evaluated based on:

-   DOM manipulation logic
-   Correct event handling
-   Functionality of interactive components
-   Usability
-   Accessibility considerations
-   Browser console testing
-   Quality of source-code explanation

------------------------------------------------------------------------

## ▶️ How to Run

1.  Download or clone the project.
2.  Open the project folder.
3.  Open `index.html` in a modern web browser.
4.  Navigate through the website.
5.  Test each interactive component.
6.  Open **Developer Tools → Console** to check for JavaScript errors.

For the best development experience, the project can also be opened
using a local development server such as **VS Code Live Server**.

------------------------------------------------------------------------


