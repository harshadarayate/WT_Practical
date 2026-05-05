package com.example.inventory_system;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.web.bind.annotation.*;
import java.util.List;
import java.util.Optional;

@RestController
@RequestMapping("/api/products")
public class ProductController {

    @Autowired
    private ProductRepository repo;

    // 1. CREATE
    @PostMapping
    public Product addProduct(@RequestBody Product product) {
        return repo.save(product);
    }

    // 2. READ (All)
    @GetMapping
    public List<Product> getAllProducts() {
        return repo.findAll();
    }

    // 3. READ (Single by ID)
    @GetMapping("/{id}")
    public Optional<Product> getProductById(@PathVariable String id) {
        return repo.findById(id);
    }

    // 4. UPDATE
    @PutMapping("/{id}")
    public Product updateProduct(@PathVariable String id, @RequestBody Product productDetails) {
        productDetails.setId(id); // Ensure the ID stays the same
        return repo.save(productDetails);
    }

    // 5. DELETE
    @DeleteMapping("/{id}")
    public String deleteProduct(@PathVariable String id) {
        repo.deleteById(id);
        return "Product deleted successfully!";
    }
}