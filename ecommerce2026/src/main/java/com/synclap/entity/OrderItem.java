package com.synclap.entity;

import com.fasterxml.jackson.annotation.JsonIgnore;
import jakarta.persistence.*;
import jakarta.validation.constraints.DecimalMin;
import jakarta.validation.constraints.Min;
import jakarta.validation.constraints.NotBlank;
import jakarta.validation.constraints.NotNull;
import lombok.*;

import java.math.BigDecimal;

@Entity
@Table(name = "order_items")
@Getter
@Setter
@NoArgsConstructor
@AllArgsConstructor
@Builder
public class OrderItem {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @NotNull(message = "Đơn hàng không được để trống")
    @ManyToOne(fetch = FetchType.LAZY)
    @JoinColumn(name = "order_id", nullable = false)
    @JsonIgnore
    private Order order;

    @NotNull(message = "Phiên bản sản phẩm không được để trống")
    @ManyToOne(fetch = FetchType.LAZY)
    @JoinColumn(name = "product_variant_id", nullable = false)
    private ProductVariant productVariant;

    @NotBlank(message = "Tên sản phẩm snapshot không được để trống")
    @Column(name = "product_name", nullable = false, length = 255)
    private String productName;

    @NotBlank(message = "Thông số snapshot không được để trống")
    @Column(name = "variant_info", nullable = false, length = 255)
    private String variantInfo;

    @NotNull(message = "Giá không được để trống")
    @DecimalMin(value = "0.0", message = "Giá phải >= 0")
    @Column(nullable = false, precision = 15, scale = 2)
    private BigDecimal price;

    @Min(value = 1, message = "Số lượng mua tối thiểu là 1")
    @Column(nullable = false)
    private Integer quantity;

    @NotNull(message = "Thành tiền không được để trống")
    @DecimalMin(value = "0.0", message = "Thành tiền phải >= 0")
    @Column(nullable = false, precision = 15, scale = 2)
    private BigDecimal subtotal;
}
