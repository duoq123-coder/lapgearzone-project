package com.synclap.entity;

import com.fasterxml.jackson.annotation.JsonIgnore;
import jakarta.persistence.*;
import jakarta.validation.constraints.DecimalMin;
import jakarta.validation.constraints.Min;
import jakarta.validation.constraints.NotBlank;
import jakarta.validation.constraints.NotNull;
import lombok.*;
import org.hibernate.annotations.CreationTimestamp;
import org.hibernate.annotations.UpdateTimestamp;

import java.math.BigDecimal;
import java.time.LocalDateTime;

@Entity
@Table(name = "product_variants", indexes = {
    @Index(name = "idx_variants_color_ram", columnList = "color, ram"),
    @Index(name = "idx_variants_stock", columnList = "stock_quantity")
})
@Getter
@Setter
@NoArgsConstructor
@AllArgsConstructor
@Builder
public class ProductVariant {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @NotNull(message = "Sản phẩm không được để trống")
    @ManyToOne(fetch = FetchType.LAZY)
    @JoinColumn(name = "product_id", nullable = false)
    @JsonIgnore
    private Product product;

    @NotBlank(message = "Mã SKU không được để trống")
    @Column(nullable = false, unique = true, length = 100)
    private String sku;

    @NotBlank(message = "Màu sắc không được để trống")
    @Column(nullable = false, length = 50)
    private String color;

    @NotBlank(message = "Dung lượng RAM không được để trống")
    @Column(nullable = false, length = 50)
    private String ram;

    @NotBlank(message = "Dung lượng ổ cứng không được để trống")
    @Column(nullable = false, length = 50)
    private String storage;

    @Column(length = 100)
    private String cpu;

    @Column(length = 100)
    private String gpu;

    @DecimalMin(value = "0.0", message = "Giá phụ trội phải >= 0")
    @Column(name = "additional_price", nullable = false, precision = 15, scale = 2)
    @Builder.Default
    private BigDecimal additionalPrice = BigDecimal.ZERO;

    @Min(value = 0, message = "Số lượng kho phải >= 0")
    @Column(name = "stock_quantity", nullable = false)
    @Builder.Default
    private Integer stockQuantity = 0;

    @Column(name = "image_url", length = 500)
    private String imageUrl;

    @CreationTimestamp
    @Column(name = "created_at", updatable = false)
    private LocalDateTime createdAt;

    @UpdateTimestamp
    @Column(name = "updated_at")
    private LocalDateTime updatedAt;
}
