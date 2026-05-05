package com.example.inventory_system;

import org.springframework.data.annotation.Id;
import org.springframework.data.mongodb.core.mapping.Document;

@Document(collection = "products")
public class Product {
    @Id
    private String id;
    private String name;
    private double price;
    private int quantity;

    // Right-click in your IDE to "Generate Getters and Setters" 
    // or type them out manually for id, name, price, and quantity.

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public double getPrice() {
        return price;
    }

    public void setPrice(double price) {
        this.price = price;
    }

    public int getQuantity() {
        return quantity;
    }

    public void setQuantity(int quantity) {
        this.quantity = quantity;
    }

    // Add this inside your Product class in Product.java
    public void setId(String id) {
        this.id = id;
    }

    public String getId() {
        return id;
    }
}