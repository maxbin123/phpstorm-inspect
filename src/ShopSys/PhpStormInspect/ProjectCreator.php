<?php

namespace ShopSys\PhpStormInspect;

class ProjectCreator {
    private $projectDirectory;
    private $resourcesDirectory;

    public function __construct($projectDirectory)
    {
        $this->projectDirectory   = $projectDirectory;
        $this->resourcesDirectory = __DIR__ . '/../../../resources';
    }

    public function repairProjectIfNeeded() {
        $dotIdeaFolder = $this->projectDirectory . '/.idea';
        if (!\file_exists($dotIdeaFolder) && !\mkdir($dotIdeaFolder) && !\is_dir($dotIdeaFolder)) {
            throw new \RuntimeException(\sprintf("Failed to create '%s'", $dotIdeaFolder));
        }

        $miscStub = $this->resourcesDirectory . '/misc.xml';
        $miscFile = $this->projectDirectory . '/.idea/misc.xml';
        if (!\file_exists($miscFile) && !\copy($miscStub, $miscFile) && !\file_exists($miscFile)) {
            throw new \RuntimeException(\sprintf("Failed to create '%s'", $miscFile));
        }

        $modulesFile = $this->projectDirectory . '/.idea/modules.xml';
        if (!\file_exists($modulesFile)) {
            $modulesStub       = $this->resourcesDirectory . '/modules.xml';
            $defaultModuleStub = $this->resourcesDirectory . '/default.iml';
            $defaultModuleFile = $this->projectDirectory . '/.idea/default.xml';

            $copied = \copy($defaultModuleStub, $defaultModuleFile) && \copy($modulesStub, $modulesFile);
            $exists = \file_exists($defaultModuleFile) && \file_exists($modulesFile);
            if (!$copied && !$exists) {
                throw new \RuntimeException(\sprintf("Failed to create '%s', '%s'", $modulesFile, $defaultModuleFile));
            }
        }

        $profileStub = $this->resourcesDirectory . '/Project_Default.xml';
        $profileFile = $this->projectDirectory . '/.idea/inspectionProfiles/Project_Default.xml';
        if (!\file_exists($profileFile)) {
            $profileDirectory = \dirname($profileFile);
            if (!\file_exists($profileDirectory) && !\mkdir($profileDirectory) && !\is_dir($profileDirectory)) {
                throw new \RuntimeException(\sprintf("Failed to create '%s'", $miscFile));
            }
            if (!\copy($profileStub, $profileFile) && !\file_exists($profileFile)) {
                throw new \RuntimeException(\sprintf("Failed to create '%s'", $profileFile));
            }
        }
    }
}