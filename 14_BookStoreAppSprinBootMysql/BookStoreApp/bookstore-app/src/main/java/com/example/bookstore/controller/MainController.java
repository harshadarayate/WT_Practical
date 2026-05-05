package com.example.bookstore.controller;

import com.example.bookstore.model.Book;
import com.example.bookstore.model.User;
import com.example.bookstore.repository.BookRepository;
import com.example.bookstore.repository.UserRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.ModelAttribute;
import org.springframework.web.bind.annotation.RequestParam;

@Controller
public class MainController {

    @Autowired
    private BookRepository bookRepository;

    @Autowired
    private UserRepository userRepository;

    // 1) Home Page
    @GetMapping("/")
    public String home() {
        return "index";
    }

    // 2) Catalog Page
    @GetMapping("/catalog")
    public String catalog(Model model) {
        model.addAttribute("books", bookRepository.findAll());
        return "catalog"; // This must match catalog.html exactly
    }

    // 3) Login Page
    @GetMapping("/login")
    public String login() {
        return "login";
    }

    // 4) Registration Page (Display Form)
    @GetMapping("/register")
    public String showRegistrationForm() {
        return "register";
    }

    // Handle Registration Database Action
    @PostMapping("/register")
    public String registerUser(@ModelAttribute User user) {
        userRepository.save(user); // Saves the user data to MySQL
        return "redirect:/login"; // Redirects to login page after success
    }

    @PostMapping("/login")
public String processLogin(@RequestParam String email, 
                           @RequestParam String password, 
                           Model model) {
    
    User user = userRepository.findByEmail(email);
    
    if (user != null && user.getPassword().equals(password)) {
        // Successful login - redirect to catalog
        return "redirect:/catalog";
    } else {
        // Failed login - show error message on login page
        model.addAttribute("error", "Invalid email or password");
        return "login";
    }
}

}