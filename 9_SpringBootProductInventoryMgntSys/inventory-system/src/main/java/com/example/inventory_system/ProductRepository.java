package com.example.inventory_system;

import org.springframework.data.mongodb.repository.MongoRepository;

public interface ProductRepository extends MongoRepository<Product, String> {
    // This interface now has CRUD methods like save(), findAll(), deleteById()
}