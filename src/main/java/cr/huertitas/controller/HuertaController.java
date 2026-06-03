package cr.huertitas.controller;

import cr.huertitas.model.Reservacion;
import jakarta.validation.Valid;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.validation.BindingResult;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.ModelAttribute;
import org.springframework.web.bind.annotation.PostMapping;

@Controller
public class HuertaController {

    @GetMapping("/")
    public String index(Model model) {
        model.addAttribute("reservacion", new Reservacion());
        return "index";
    }

    @PostMapping("/reservar")
    public String reservar(
            @Valid @ModelAttribute("reservacion") Reservacion reservacion,
            BindingResult result,
            Model model) {

        if (result.hasErrors()) {
            return "index";
        }

        model.addAttribute("exito", true);
        model.addAttribute("nombre", reservacion.getNombre());
        model.addAttribute("reservacion", new Reservacion());
        return "index";
    }
}
