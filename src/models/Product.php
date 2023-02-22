<?php

/**
 *    Blocks Engine - Template for a market place.
 *    Copyright (C) 2022-2023 João Torres
 *
 *    This program is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU Affero General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    This program is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *    GNU Affero General Public License for more details.
 *
 *    You should have received a copy of the GNU Affero General Public License
 *    along with this program. If not, see <https://www.gnu.org/licenses/>.
 *
 * @package TorresDeveloper\\BlocksEngine\\Models
 * @author João Torres <torres.dev@disroot.org>
 * @copyright Copyright (C) 2022-2023 João Torres
 * @license https://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License
 * @license https://opensource.org/licenses/AGPL-3.0 GNU Affero General Public License version 3
 *
 * @since 0.0.3
 * @version 0.0.1
 */

declare(strict_types=1);

namespace Bloqs\Models;

use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\UriInterface;
use TorresDeveloper\MVC\Model\RESTModel;
use TorresDeveloper\MVC\Model\Table;

use function Bloqs\Core\api;

/**
 * Bloq Model
 *
 * @author João Torres <torres.dev@disroot.org>
 *
 * @since 0.0.3
 * @version 0.0.2
 */
#[Table("bloq")]
class Product extends RESTModel
{
    private string $name;
    private string $description;
    private Category $preference;
    private UploadedFileInterface $image;
    private bool $hasAdultConsideration;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
    public function getPreference(): Category
    {
        return $this->preference;
    }
    public function setPreference(Category $preference): void
    {
        $this->preference = $preference;
    }
    public function getImage(): UploadedFileInterface
    {
        return $this->image;
    }
    public function setImage(UploadedFileInterface $image): void
    {
        $this->image = $image;
    }
    public function getHasAdultConsideration(): bool
    {
        return $this->hasAdultConsideration;
    }
    public function setHasAdultConsideration(bool $hasAdultConsideration): void
    {
        $this->hasAdultConsideration = $hasAdultConsideration;
    }

    public static function fromRESTJSON(UriInterface $endpoint, array $json): ?static
    {
        $o = new static($endpoint);

        if (($json["@type"] ?? null) !== "Product") {
            return null;
        }

        $o->setId((string) $json["id"]);
        $o->setName($json["name"]);
        $o->setDescription($json["description"]);
        $o->setPreference(Category::getFinder(api())->withID($json["category"])->run()->current());
        $o->setImage($json["image"]);
        $o->setHasAdultConsideration($json["hasAdultConsideration"]);

        return $o;
    }

    public function toArray(): array
    {
        //var_dump($this->image->getStream()->getMetadata());
        return [
            "id" => $this->id ?? null,
            "name" => $this->name,
            "description" => $this->description,
            "category" => $this->preference->getId(),
            "image" => !isset($this->image) ? null : new \CURLFile($this->image->getClientFilename(), $this->image->getClientMediaType()),
            "hasAdultConsideration" => (int) $this->hasAdultConsideration
        ];
    }

    public function __toString(): string
    {
        return "";
    }

    public function jsonSerialize(): mixed
    {
    }
}
