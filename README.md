<p align="center"><a href="https://korporatio.com/" target="_blank"><img src="https://b3074478.smushcdn.com/3074478/wp-content/uploads/2018/01/A4-Copy-2-1.png?lossy=1&strip=1&webp=1" width="400" alt="Laravel Logo"></a></p>


## Shopping Cart Solution

    This solution implements a shopping cart system using Livewire in Laravel, which dynamically updates the cart and displays the number of items in the cart with a cart icon. It also provides a fully functional cart page to review items, manage quantities, and place orders.


## Features

- Dynamic Cart Count: The cart icon in the navigation updates in real-time to show the number of items added to the cart.
- Livewire Integration: Livewire components handle cart actions such as adding/removing products and updating the cart.
- Responsive Design: The cart page is fully responsive, with mobile-first design, thanks to Tailwind CSS.
- Cart Management: View cart items, their prices, and quantities. Users can also remove items or place an order.
- Session Storage: The cart is stored in the session, allowing persistence across requests.

## Prerequisites
- Laravel: This solution requires Laravel 8+.
- Livewire: Livewire is used to handle front-end actions like adding/removing products and updating the cart dynamically.
- Tailwind CSS: For styling the user interface.


## Minimum system requirements

- PHP >= 8.2
- Laravel 12

## How to run the application

Below are the steps you need to successfully setup and run the application.

- Clone the app from the repository and cd into the root directory of the app

Now, the most important, build and start the package dependencies by running
`composer install`

Composer will start doing its magic. All required dependencies, will be installed.

#  Install NPM Dependencies (Optional for Assets)
 If you're using Tailwind CSS, run the following commands to compile the front-end assets:

``` bash
npm install
npm run dev

```

##  Set Up .env File


``` bash
 cp .env.example .env
```
Generate an application key:

```bash
php artisan migrate
```

Generate dummy data:

```bash
php artisan db:seed
```

Lunch the project

```bash
php artisan serve
```

### Features Walkthrough

    Features Walkthrough
    Cart Icon Component
    The cart icon dynamically shows the number of items in the cart. The icon is part of a Livewire component called CartIcon. It listens to the session cart and updates the badge with the number of items in the cart.

Component Location:
app/Http/Livewire/CartIcon.php

This component retrieves the cart data from the session and displays the count.

## Cart Page
    The cart page is designed using Tailwind CSS for a modern, responsive layout. It lists the products added to the cart, along with their quantity and price. Users can also remove items or place an order.

## Component Location:
    resources/views/livewire/cart.blade.php

Cart Actions
- Add to Cart: When a user clicks "Add to Cart", the product is added to the session-based cart, and the cart icon is updated with the new count.

- Remove from Cart: Users can remove items from the cart using a simple "X" button.

- Place Order: Once users are ready, they can place an order by clicking the "Order Now" button, which you can later integrate with your order processing logic.

## Components Used
- CartIcon Component: Displays the number of items in the cart dynamically.
- CartPage Component: Displays all items in the cart with options to remove and place orders.
- Cart Header Component: A reusable header component that displays success/error messages and cart-related information.
- Livewire Event Handling
- The CartIcon component listens to the cart session and updates dynamically when items are added or removed. Livewire events like cartUpdated can be dispatched to handle updates to the cart's contents.

## Usage
    Adding Products to the Cart
    The products are listed with an "Add to Cart" button. Clicking this button triggers the addToCart method in the Livewire component. The cart is stored in the session and persists between requests.

## Viewing the Cart
- When users click the "Cart" button in the navigation, they are redirected to the cart page where they can see all the items added to the cart, their details (name, price, image), and manage their cart (remove items).

## Cart Actions
- Remove Items: Items can be removed by clicking the "X" button next to each product in the cart.
- Place Order: Users can place an order once they’ve reviewed the items in their cart.
## Future Improvements
- Add User Authentication: Implement user authentication so that users can log in and save their carts across sessions.
- Database Integration: Currently, the cart is stored in the session. It can be expanded to allow carts to be stored in the database for logged-in users.
- Order Processing: Implement the actual order placement functionality to save orders in the database and notify users via email.
- Quantity Management: Allow users to change the quantity of items in their cart rather than just adding/removing them.
## Livewire Best Practices
- Livewire makes working with dynamic data seamless by syncing state between the backend and the frontend.
- Always validate and sanitize user inputs, especially when dealing with forms or user-generated content.
- Use event listeners to keep components in sync when state changes.

