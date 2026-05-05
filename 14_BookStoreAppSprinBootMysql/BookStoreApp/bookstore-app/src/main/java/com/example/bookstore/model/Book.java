package com.example.bookstore.model;

import jakarta.persistence.*;
import lombok.Data;

@Entity
@Data // Automatically generates getters/setters via Lombok
@Table(name = "books")
public class Book {
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    
    private Long id;
    private String title;
    private String author;
    private Double price;
    private String description;
    private String category;
}