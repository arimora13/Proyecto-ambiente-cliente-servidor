package cr.huertitas.model;

import jakarta.validation.constraints.*;

public class Reservacion {

    @NotBlank(message = "El nombre es obligatorio")
    private String nombre;

    @NotBlank(message = "El contacto es obligatorio")
    @Email(message = "Ingrese un correo electrónico válido")
    private String contacto;

    @NotNull(message = "La fecha es obligatoria")
    private String fecha;

    @NotNull(message = "El número de personas es obligatorio")
    @Min(value = 1, message = "Mínimo 1 persona")
    @Max(value = 100, message = "Máximo 100 personas")
    private Integer personas;

    private String comentarios;

    public Reservacion() {}

    public String getNombre() { return nombre; }
    public void setNombre(String nombre) { this.nombre = nombre; }

    public String getContacto() { return contacto; }
    public void setContacto(String contacto) { this.contacto = contacto; }

    public String getFecha() { return fecha; }
    public void setFecha(String fecha) { this.fecha = fecha; }

    public Integer getPersonas() { return personas; }
    public void setPersonas(Integer personas) { this.personas = personas; }

    public String getComentarios() { return comentarios; }
    public void setComentarios(String comentarios) { this.comentarios = comentarios; }
}
