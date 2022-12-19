<?php

namespace App\Entity;

class ResetPassword
{
  private ?string $email;

  private ?string $resetToken;

  private ?string $password;

  private ?string $confirmPassword;

  public function getEmail(): ?string
  {
    return $this->email;
  }

  public function setEmail($email): self
  {
    $this->email = $email;
    return $this;
  }

  public function getResetToken(): ?string
  {
    return $this->resetToken;
  }

  public function setResetToken($resetToken): self
  {
    $this->resetToken = $resetToken;
    return $this;
  }

  public function getPassword(): ?string
  {
    return $this->password;
  }

  public function setPassword($password): self
  {
    $this->password = $password;
    return $this;
  }

  public function getConfirmPassword(): ?string
  {
    return $this->confirmPassword;
  }

  public function setConfirmPassword($confirmPassword): self
  {
    $this->confirmPassword = $confirmPassword;
    return $this;
  }
}
